<?php

namespace Syntro\SilverStripeElementalBaseitems\Elements;

use SilverStripe\Forms\ReadonlyField;
use SilverStripe\Forms\OptionsetField;
use SilverStripe\Forms\TextField;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Assets\Image;
use DNADesign\Elemental\Models\BaseElement;
use SilverStripe\Forms\FieldList;
use Syntro\SilverStripeElementalBaseitems\Control\BootstrapElementController;

/**
 * This Element abstracts the DNADesign baseelement to apply several additional
 * options useful for bootstrap sections
 *
 * @author Matthias Leutenegger <hello@syntro.ch>
 */
class BootstrapSectionBaseElement extends BaseElement
{

    /**
     * This defines the block name in the CSS
     *
     * @config
     * @var string
     */
    private static $block_name = 'section';

    /**
     * @var string
     */
    private static $controller_class = BootstrapElementController::class;

    /**
     * @var string
     */
    private static $controller_template = 'ElementHolder';


    /**
     * Location of Templates
     *
     * @var string
     */
    private static $template_root = 'Syntro\\BootstrapElemental\\';

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
        'white'
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
    private static $link_colors_by_text = [];

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
    public function getCMSFields()
    {
        $this->beforeUpdateCMSFields(function (FieldList $fields) {

            $useImage = static::config()->get('allow_image_background');

            // add a selection field for Color or image
            $fields->removeByName('BackgroundType');
            if ($useImage || count($this->getTranslatedOptionsFor('background_colors')) > 1) {
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
            }


            // add a dropdown with available colors
            $fields->removeByName('BackgroundColorLabel');
            $fields->addFieldToTab(
                'Root.Settings',
                $bgColorField = $this->createColorSelectField(
                    'BackgroundColorLabel',
                    _t(
                        __CLASS__ . '.BACKGROUNDCOLOR',
                        'Background color'
                    ),
                    'background_colors'
                ),
                'ExtraClass'
            );
            /** @phpstan-ignore-next-line */
            $bgColorField->hideIf('BackgroundType')->isEqualTo('image');


            // Add additional fields for setting an Image Background
            $fields->removeByName([
                'TextColorLabel',
                'BGImage'
            ]);
            if ($useImage) {
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
                /** @phpstan-ignore-next-line */
                $backgroundImage->hideUnless('BackgroundType')->isEqualTo('image');

                $fields->addFieldToTab(
                    'Root.Settings',
                    $textColor = $this->createColorSelectField(
                        'TextColorLabel',
                        _t(
                            __CLASS__ . '.TEXTCOLOR',
                            'Text color'
                        ),
                        'text_colors'
                    ),
                    'ExtraClass'
                );
                /** @phpstan-ignore-next-line */
                $textColor->hideUnless('BackgroundType')->isEqualTo('image');
            }
        });



        return parent::getCMSFields();
    }


    /**
     * getBackgroundOptions - return possible options for background
     *
     * @return array
     */
    public function getBackgroundOptions()
    {
        $useImage = static::config()->get('allow_image_background');
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
     * @param  string $configOption the name of the config value
     * @return array
     */
    public function getTranslatedOptionsFor($configOption)
    {
        $values = static::config()->get($configOption);
        $selection = [];
        foreach ($values as $valueKey => $valueName) {
            $selection[$valueKey] = _t(
                __CLASS__ . '.' . $valueKey,
                $valueName
            );
        }
        $selection['default'] = _t(
            __CLASS__ . '.DEFAULT',
            'Default'
        );
        return $selection;
    }

    /**
     * createColorSelectField - generates a field to be displayed in the cms
     * allowing the selection of values from a color list
     *
     * @param  string $name                the name of the field
     * @param  string $title               the title of the field
     * @param  string $colorListFromConfig the config option containing the list
     * @return DropdownField|TextField
     */
    public function createColorSelectField($name,$title,$colorListFromConfig)
    {
        $options = $this->getTranslatedOptionsFor($colorListFromConfig);
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
        $default = static::config()->get('default_background_color');
        $default = $default ? $default : null;
        $bgColors = static::config()->get('background_colors');
        if ($this->BackgroundType == 'image' && static::config()->get('allow_image_background')) {
            return null;
        } elseif ($this->BackgroundColorLabel == 'default') {
            return $default;
        }
        return isset($bgColors[$this->BackgroundColorLabel]) ? $this->BackgroundColorLabel : $default;
    }

    /**
     * getTextColor - return the text color label for this section
     *
     * @return string|null
     */
    public function getTextColor()
    {
        $default = static::config()->get('default_text_color');
        $default = $default ? $default : null;
        $textColors = static::config()->get('text_colors');
        if ($this->BackgroundType == 'image' && static::config()->get('allow_image_background')) {
            return isset($textColors[$this->TextColorLabel]) ? $this->TextColorLabel : $default;;
        }

        $colorsByBackground = static::config()->get('text_colors_by_background');

        return isset($colorsByBackground[$this->BackgroundColorLabel]) ? $colorsByBackground[$this->BackgroundColorLabel] : $default;
    }

    /**
     * getBackgroundColorClass - retrun the background color class for this section
     *
     * @return string
     */
    public function getBackgroundColorClass()
    {
        $bgColor = $this->getBackgroundColor();
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
        $textColor = $this->getTextColor();
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
        $linkColorByBackground = static::config()->get('link_colors_by_background');
        if (isset($linkColorByBackground[$this->getBackgroundColor()])) {
            return $linkColorByBackground[$this->getBackgroundColor()];
        }
        $textColor = $this->getTextColor();
        $linkColorByText = static::config()->get('link_colors_by_text');
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
        if ($this->BackgroundType == 'image' && static::config()->get('allow_image_background')) {
            return $this->BGImage;
        }
        return null;
    }





    //
    // /**
    //  * getBackgroundColor - returns the background Color
    //  *
    //  * @return string|null
    //  */
    // public function getComputedBackgroundColor()
    // {
    //     $availableColors = static::config()->get('background_colors');
    //     if (
    //         $this->BackgroundColor &&
    //         isset($availableColors[$this->BackgroundColor]) &&
    //         $this->BackgroundColor !== '_image' &&
    //         $this->BackgroundColor !== 'default'
    //     ) {
    //         return $this->BackgroundColor;
    //     }
    //     return null;
    // }
    //
    // /**
    //  * getTextColor - returns the current text color
    //  *
    //  * @return string|null
    //  */
    // public function getComputedTextColor()
    // {
    //     $availableColors = static::config()->get('text_colors');
    //     $bgToTextColor = static::config()->get('text_colors_by_background');
    //     if (
    //         $this->TextColor &&
    //         isset($availableColors[$this->TextColor]) &&
    //         $this->BackgroundColor === '_image'
    //     ) {
    //         return $this->TextColor;
    //     } elseif (
    //         $this->BackgroundColor &&
    //         $this->BackgroundColor !== 'default' &&
    //         $this->BackgroundColor !== '_image' &&
    //         isset($bgToTextColor[$this->BackgroundColor])
    //     ) {
    //         return $bgToTextColor[$this->BackgroundColor];
    //     }
    //     return null;
    // }
    //
    // /**
    //  * getBackgroundImage - returns the durrent background image
    //  *
    //  * @return Image|null
    //  */
    // public function getComputedBackgroundImage()
    // {
    //     if (
    //         static::config()->get('allow_image_background') &&
    //         $this->BackgroundColor === '_image' &&
    //         $this->BGImageID != 0
    //     ) {
    //         return $this->BGImage;
    //     }
    //     return null;
    // }








    /**
     * getRenderTemplates - returns the templates used to render this element
     *
     * @param string $suffix
     *
     * @return array
     */
    public function getRenderTemplates($suffix = '')
    {
        $templates =  parent::getRenderTemplates($suffix);
        $templateRoot = static::config()->get('template_root');
        $relocatedTemplates = [];
        foreach ($templates as $key => $value) {
            $namespace = explode('\\',$value);
            $relocatedTemplates[] = $templateRoot . array_pop($namespace);
        }
        $relocatedTemplates['type'] = 'Blocks';
        return $relocatedTemplates;
    }

    /**
     * getSubTemplate - returns the template location for a given sub-template
     *
     * @param  string $itemName the items Name
     * @return string
     */
    public function getSubTemplate($itemName = '')
    {
        $templateRoot = static::config()->get('template_root');
        $ElementName = explode('\\', static::class);
        $ElementName = array_pop($ElementName);
        return $templateRoot . 'Blocks\\' . $ElementName . '\\' . $itemName;
    }


    /**
     * getElementName - retrieve the block-name
     *
     * @return string
     */
    public function getElementName()
    {
        return static::config()->get('block_name');
    }

}
