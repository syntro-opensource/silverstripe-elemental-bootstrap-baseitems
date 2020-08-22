<?php
namespace Syntro\SilverStripeElementalBaseitems\Elements;

use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Forms\HTMLEditor\TinyMCEConfig;
use SilverStripe\ORM\FieldType\DBField;
use Syntro\SilverStripeElementalBaseitems\Elements\BootstrapSectionBaseElement;


/**
 * Basic content element using Bootstrap markup
 */
class BootstrapContentSectionElement extends BootstrapSectionBaseElement
{
    private static $icon = 'font-icon-block-content';

    private static $db = [
        'HTML' => 'HTMLText'
    ];

    private static $table_name = 'ContentSectionElement';

    private static $singular_name = 'content block';

    private static $plural_name = 'content blocks';

    private static $description = 'HTML text block';


    private static $background_colors = [
        'light' => 'Lightgrey',
        'dark' => 'Dark',
        'default' => 'Default'
    ];

    private static $text_colors_by_background = [
        'dark' => 'white'
    ];

    private static $text_colors = [
        'white' => 'White',
        'dark' => 'Dark',
        'default' => 'Default'
    ];

    /**
     * Re-title the HTML field to Content
     *
     * {@inheritDoc}
     */
    public function getCMSFields()
    {
        $this->beforeUpdateCMSFields(function (FieldList $fields) {
            /** @var HTMLEditorField $editorField */
            $editorField = $fields->fieldByName('Root.Main.HTML');
            $editorField->setTitle(_t(__CLASS__ . '.ContentLabel', 'Content'));
        });

        return parent::getCMSFields();
    }

    public function getSummary()
    {
        return DBField::create_field('HTMLText', $this->HTML)->Summary(20);
    }

    protected function provideBlockSchema()
    {
        $blockSchema = parent::provideBlockSchema();
        $blockSchema['content'] = $this->getSummary();
        return $blockSchema;
    }

    public function getType()
    {
        return _t(__CLASS__ . '.BlockType', 'Content');
    }
}
