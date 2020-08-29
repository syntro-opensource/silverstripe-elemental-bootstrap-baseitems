<?php
namespace Syntro\SilverStripeElementalBaseitems\Elements;

use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Forms\HTMLEditor\TinyMCEConfig;
use SilverStripe\ORM\FieldType\DBField;
use Syntro\SilverStripeElementalBaseitems\Elements\BootstrapSectionBaseElement;


/**
 * Basic content element using Bootstrap markup
 */
class BootstrapImageSectionElement extends BootstrapSectionBaseElement
{
    /**
     * This defines the block name in the CSS
     *
     * @config
     * @var string
     */
    private static $block_name = 'image-section';

    private static $icon = 'font-icon-block-file';

    private static $db = [];

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
     * {@inheritDoc}
     */
    public function getCMSFields()
    {
        $this->beforeUpdateCMSFields(function (FieldList $fields) {
            $fields->addFieldToTab(
                'Root.Main',
                $uploadfield = UploadField::create(
                    'Image',
                    'Image'
                )
            );
            $uploadfield->setAllowedMaxFileNumber(1);
            $uploadfield->setFolderName('Uploads/ImageSections');
        });

        return parent::getCMSFields();
    }

    public function getSummary()
    {
        /** @var Image|null $image */
        $image = $this->Image();
        if ($image && $image->exists() && $image->getIsImage()) {
            return $image->Filename;
        }
        return parent::getSummary();
    }

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

    public function getType()
    {
        return _t(__CLASS__ . '.BlockType', 'Image');
    }
}
