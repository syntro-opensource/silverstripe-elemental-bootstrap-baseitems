<?php

namespace Syntro\SilverStripeElementalBaseitems\Extensions;

use SilverStripe\Forms\ReadonlyField;
use SilverStripe\Forms\OptionsetField;
use SilverStripe\Forms\TextField;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Assets\Image;
use SilverStripe\ORM\DataExtension;
use DNADesign\Elemental\Models\BaseElement;
use SilverStripe\Forms\FieldList;
use Syntro\SilverStripeElementalBaseitems\Control\BootstrapElementController;


/**
 * This Extension abstracts the DNADesign baseelement to apply several additional
 * options useful for bootstrap sections
 *
 * @author Matthias Leutenegger <hello@syntro.ch>
 */
class BootstrapBaseElementExtension extends DataExtension
{

    /**
     * This defines the block name in the CSS
     *
     * @config
     * @var string
     */
    private static $block_name = 'section';

    // /**
    //  * @var string
    //  */
    // private static $controller_class = BootstrapElementController::class;

    // /**
    //  * @config
    //  * @var string
    //  */
    // private static $controller_template = 'ElementHolder';


    // /**
    //  * Location of Templates
    //  *
    //  * @config
    //  * @var string
    //  */
    // private static $template_root = 'Syntro\\BootstrapElemental\\';

    /**
     * Set to false to prevent an in-line edit form from showing in an elemental area. Instead the element will be
     * clickable and a GridFieldDetailForm will be used.
     *
     * @config
     * @var bool
     */
    private static $inline_editable = false;

    /**
     * Override this on your custom elements to specify a CSS icon class
     *
     * @var string
     */
    private static $icon = 'font-icon-block-layout';

    /**
     * Describe the purpose of this element
     *
     * @config
     * @var string
     */
    private static $description = 'Bootstrap Section Base element class';

    /**
     * if true, the background displays a default label.
     *
     * @config
     * @var boolean
     */
    private static $add_default_background_color = true;

    /**
     * The default background color. If set, there will always be a class
     * rendered
     *
     * @config
     * @var string|null
     */
    private static $default_background_color = null;

    /**
     * Available background colors for this Element
     *
     * @config
     * @var array
     */
    private static $background_colors = [];

    /**
     * set to false if image option should not show up
     *
     * @config
     * @var bool
     */
    private static $allow_image_background = true;

    /**
     * The default text color. If set, there will always be a class rendered
     *
     * @config
     * @var string|null
     */
    private static $default_text_color = null;

    /**
     * Available text colors for this Element. They are not used unless
     * an image is applied as a section Background, where it becomes important
     * to let the user choose the text color (as it depends on the Image).
     *
     * @config
     * @var array
     */
    private static $text_colors = [
        'white' => 'White'
    ];

    /**
     * Color mapping from background color. This is mainly intended
     * to set a default color on the section-level, ensuring text is readable.
     * Colors of subelementscan be added via templates
     *
     * @config
     * @var array
     */
    private static $text_colors_by_background = [];

    /**
     * Color mapping from text color to link color. This array can be used to
     * specify colors of buttons or links depending on the chosen background
     * color. if no value is specified, this will fall back to the text color
     *
     * @config
     * @var array
     */
    private static $link_colors_by_text = [
        'white' => 'light'
    ];

    /**
     * Color mapping from background to link color. This option can be used for
     * special cases where the same text color should have different link colors
     * (i.e. two dark background colors)
     *
     * @config
     * @var array
     */
    private static $link_colors_by_background = [];



    /**
     * @var array
     */
    private static $db = [
        'BackgroundColorLabel' => 'Varchar(50)',
        'BackgroundType' => 'Enum("image,color","image")',
        'TextColorLabel' => 'Varchar(50)'
        // 'Template' => 'Varchar(255)'
    ];

    /**
     * @var array
     */
    private static $defaults = [
        'BackgroundColorLabel' => 'default',
        'TextColorLabel' => 'default',
        'BackgroundType' => 'color'
    ];

    /**
     * @var array
     */
    private static $has_one = [
        'BGImage' => Image::class,
    ];

    /**
     * @var array
     */
    private static $owns = [
        'BGImage'
    ];

    private static $singular_name = 'bootstrap section';

    private static $plural_name = 'bootstrap sections';

    private static $table_name = 'BootstrapSectionElement';

    /**
     * getCMSFields
     *
     * @return FieldList
     */
    public function updateCMSFields($fields)
    {
        $owner = $this->getOwner();
        /**
         * If this Block allows the Use of a background image,
         * we add an optionset and the Image field
         */
        $fields->removeByName([
            'BackgroundType',
            'TextColorLabel',
            'BGImage'
        ]);
        $useImage = $owner->config()->get('allow_image_background');
        if ($useImage) {
            // add a selection field for Color or image
            $fields->addFieldToTab(
                'Root.Settings',
                OptionsetField::create(
                    'BackgroundType',
                    _t(
                        __CLASS__ . '.BACKGROUNDTYPE',
                        'Background Type'
                    ),
                    $this->getBackgroundOptions()
                ),
                'ExtraClass'
            );

            // add an image field for Background
            $fields->addFieldToTab(
                'Root.Settings',
                $backgroundImage = UploadField::create(
                    'BGImage',
                    _t(
                        __CLASS__ . '.BACKGROUNDIMAGE',
                        'Background Image'
                    )
                )
                    ->setFolderName('Uploads/Elements/Backgrounds')
                    ->setIsMultiUpload(false),
                'ExtraClass'
            );
            $backgroundImage->hideIf('BackgroundType')->isEqualTo('color');

            $fields->addFieldToTab(
                'Root.Settings',
                $textColor = $owner->createColorSelectField(
                    'TextColorLabel',
                    _t(
                        __CLASS__ . '.TEXTCOLOR',
                        'Text color'
                    ),
                    'text_colors'
                ),
                'ExtraClass'
            );
            $textColor->hideIf('BackgroundType')->isEqualTo('color');
        }


        // add a dropdown with available colors
        $fields->removeByName('BackgroundColorLabel');
        $fields->addFieldToTab(
            'Root.Settings',
            $bgColorField = $owner->createColorSelectField(
                'BackgroundColorLabel',
                _t(
                    __CLASS__ . '.BACKGROUNDCOLOR',
                    'Background color'
                ),
                'background_colors',
                $owner->config()->get('add_default_background_color')
            ),
            'ExtraClass'
        );
        $bgColorField->hideIf('BackgroundType')->isEqualTo('image');



        return $fields;
    }


    /**
     * getBackgroundOptions - return possible options for background
     *
     * @return array
     */
    public function getBackgroundOptions()
    {
        $owner = $this->getOwner();
        $useImage = $owner->config()->get('allow_image_background');
        $options = [
            'color' => _t(
                __CLASS__ . '.COLOR',
                'Color'
            )
        ];
        if ($useImage) {
            $options['image'] = _t(
                __CLASS__ . '.IMAGE',
                'Image'
            );
        }
        return $options;

    }

    /**
     * getTranslatedOptionsFor - retrieve a config value prepped for a dropdown
     *
     * @param  string  $configOption the name of the config value
     * @param  boolean $addDefault   = true add a default label
     * @return array
     */
    public function getTranslatedOptionsFor($configOption, $addDefault = true)
    {
        $owner = $this->getOwner();
        $values = $owner->config()->get($configOption);
        $selection = [];
        foreach ($values as $valueKey => $valueName) {
            $selection[$valueKey] = _t(
                static::class . '.' . $valueKey,
                $valueName
            );
        }
        if ($addDefault) {
            $selection['default'] = _t(
                __CLASS__ . '.DEFAULT',
                'Default'
            );
        }

        return $selection;
    }

    /**
     * createColorSelectField - generates a field to be displayed in the cms
     * allowing the selection of values from a color list
     *
     * @param  string  $name                the name of the field
     * @param  string  $title               the title of the field
     * @param  boolean $addDefault          = true add a default label
     * @param  string  $colorListFromConfig the config option containing the list
     * @return DropdownField|TextField
     */
    public function createColorSelectField($name,$title,$colorListFromConfig, $addDefault = true)
    {
        $owner = $this->getOwner();
        $options = $owner->getTranslatedOptionsFor($colorListFromConfig, $addDefault);
        if (count($options) > 1) {
            $bgColorField = DropdownField::create(
                $name,
                $title,
                $options
            );
        } else {
            $bgColorField = TextField::create(
                sprintf("%s_RO", $name),
                $title
            );
            $bgColorField->setValue(_t(
                __CLASS__ . '.DEFAULT',
                'Default'
            ))->setReadOnly(true);
        }
        return $bgColorField;
    }


    /**
     * getBackgroundColor - retrun the background color label for this section
     *
     * @return string|null
     */
    public function getBackgroundColor()
    {
        $owner = $this->getOwner();
        $default = $owner->config()->get('default_background_color');
        $default = $default ? $default : null;
        $bgColors = $owner->config()->get('background_colors');
        if ($owner->BackgroundType == 'image' && $owner->config()->get('allow_image_background')) {
            return null;
        } elseif ($owner->BackgroundColorLabel == 'default') {
            return $default;
        }
        return isset($bgColors[$owner->BackgroundColorLabel]) ? $owner->BackgroundColorLabel : $default;
    }

    /**
     * getTextColor - return the text color label for this section
     *
     * @return string|null
     */
    public function getTextColor()
    {
        $owner = $this->getOwner();
        $default = $owner->config()->get('default_text_color');
        $default = $default ? $default : null;
        $textColors = $owner->config()->get('text_colors');
        if ($owner->BackgroundType == 'image' && $owner->config()->get('allow_image_background')) {
            return isset($textColors[$owner->TextColorLabel]) ? $owner->TextColorLabel : $default;
        }

        $colorsByBackground = $owner->config()->get('text_colors_by_background');

        return isset($colorsByBackground[$owner->BackgroundColorLabel]) ? $colorsByBackground[$owner->BackgroundColorLabel] : $default;
    }

    /**
     * getBackgroundColorClass - retrun the background color class for this section
     *
     * @return string
     */
    public function getBackgroundColorClass()
    {
        $owner = $this->getOwner();
        $bgColor = $owner->getBackgroundColor();
        if ($bgColor) {
            return sprintf('bg-%s',$bgColor);
        }
        return '';
    }

    /**
     * getTextColorClass - retrun the text color class for this section
     *
     * @return string
     */
    public function getTextColorClass()
    {
        $owner = $this->getOwner();
        $textColor = $owner->getTextColor();
        if ($textColor) {
            return sprintf('text-%s',$textColor);
        }
        return '';
    }

    /**
     * getTextColor - retrieve the link color class. Uses $link_colors_by_text
     * and $link_colors_by_background.
     *
     * The order is as follows:
     * 1. check if a link color by background is defined
     * 2. check if a link color by text color is defined
     * 3. return text color
     *
     * @return string
     */
    public function getLinkColor()
    {
        $owner = $this->getOwner();
        $linkColorByBackground = $owner->config()->get('link_colors_by_background');
        if (isset($linkColorByBackground[$this->getBackgroundColor()])) {
            return $linkColorByBackground[$this->getBackgroundColor()];
        }
        $textColor = $owner->getTextColor();
        $linkColorByText = $owner->config()->get('link_colors_by_text');
        if (isset($linkColorByText[$textColor])) {
            return $linkColorByText[$textColor];
        }

        return $textColor;
    }

    /**
     * getBackgroundImage - check if this section has a background image and
     * return it
     *
     * @return Image|null
     */
    public function getBackgroundImage()
    {
        $owner = $this->getOwner();
        if ($owner->BackgroundType == 'image' && $owner->config()->get('allow_image_background')) {
            return $owner->BGImage;
        }
        return null;
    }

    /**
     * getElementName - retrieve the block-name
     *
     * @return string
     */
    public function getElementName()
    {
        $owner = $this->getOwner();
        return $owner->config()->get('block_name');
    }

}
