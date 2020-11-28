<?php

namespace Syntro\SilverStripeElementalBaseitems\Elements;

use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Forms\HTMLEditor\TinyMCEConfig;
use SilverStripe\ORM\FieldType\DBField;
// use Syntro\SilverStripeElementalBaseitems\Elements\BootstrapSectionBaseElement;
use DNADesign\Elemental\Models\BaseElement;

/**
 * Basic image element using Bootstrap markup
 *
 * @author Matthias Leutenegger <hello@syntro.ch>
 */
class BootstrapImageSectionElement extends BaseElement
{
    /**
     * This defines the block name in the CSS
     *
     * @config
     * @var string
     */
    private static $block_name = 'image-section';

    private static $icon = 'font-icon-block-file';

    private static $db = [
        'Caption' => 'Text'
    ];

    private static $has_one = [
        'Image' => Image::class,
    ];

    private static $owns = [
        'Image'
    ];

    private static $table_name = 'ImageSectionElement';

    private static $singular_name = 'image block';

    private static $plural_name = 'image blocks';

    private static $description = 'image block';

    private static $allow_image_background = false;

    private static $background_colors = [];

    private static $text_colors_by_background = [];

    private static $text_colors = [];

    private static $inline_editable = true;

    /**
     * Re-title the HTML field to Image
     *
     * @inheritDoc
     *
     * @return FieldList
     */
    public function getCMSFields()
    {
        $this->beforeUpdateCMSFields(function (FieldList $fields) {
            $fields->addFieldsToTab(
                'Root.Main',
                [
                    $uploadfield = UploadField::create(
                        'Image',
                        $this->fieldLabel('Image')
                    ),
                    $captionField = TextareaField::create(
                        'Caption',
                        $this->fieldLabel('Caption')
                    )
                ]
            );
            $uploadfield->setAllowedMaxFileNumber(1);
            $uploadfield->setFolderName('Uploads/ImageSections');
        });

        return parent::getCMSFields();
    }

    /**
     * fieldLabels - apply labels
     *
     * @param  boolean $includerelations = true
     * @return array
     */
    public function fieldLabels($includerelations = true)
    {
        $labels = parent::fieldLabels($includerelations);
        $labels['Image'] = _t(__CLASS__ . '.IMAGE', 'Image');
        $labels['Caption'] = _t(__CLASS__ . '.CAPTION', 'Caption');
        return $labels;
    }

    /**
     * getSummary - returns a summary of this block
     *
     * @return string
     */
    public function getSummary()
    {
        /** @var Image|null $image */
        $image = $this->Image();
        if ($image && $image->exists() && $image->getIsImage()) {
            return $this->Caption ? $this->Caption : $image->Filename;
        }
        return parent::getSummary();
    }

    /**
     * provideBlockSchema
     *
     * @return array
     */
    protected function provideBlockSchema()
    {
        $blockSchema = parent::provideBlockSchema();
        $blockSchema['content'] = $this->getSummary();
        /** @var Image|null $image */
        $image = $this->Image();
        if ($image && $image->exists() && $image->getIsImage()) {
            $blockSchema['fileURL'] = $image->CMSThumbnail()->getURL();
            $blockSchema['fileTitle'] = $image->getTitle();
        }
        return $blockSchema;
    }

    /**
     * getType
     *
     * @return string
     */
    public function getType()
    {
        return _t(__CLASS__ . '.BlockType', 'Image');
    }
}
