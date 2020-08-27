<?php

namespace Syntro\SilverStripeElementalBaseitems\Elements;

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
     * Available text colors for this Element. They are not used unless
     * an image is applied as a section Background, where it becomes important
     * to let the user choose the text color (as it depends on the Image).
     *
     * @config
     * @var array
     */
    private static $text_colors = [];

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
     * Available additional templates for this element. If populated, templates
     * can be selected when creating the Element
     *
     * @config
     * @var array
     */
    // private static $available_templates = [
    //     'default' => 'Default Look'
    // ];


    /**
     * @var array
     */
    private static $db = [
        'BackgroundColor' => 'Varchar(50)',
        'TextColor' => 'Varchar(50)',
        // 'Template' => 'Varchar(255)'
    ];

    /**
     * @var array
     */
    private static $defaults = [
        'BackgroundColor' => 'white',
        'TextColor' => 'default',
        // 'Template' => 'default'
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
            // add a dropdown with available templates
            // $fields->removeByName('Template');
            // $availableTemplates = $this->getAvailableTemplates();
            // if (count($availableTemplates) > 1) {
            //     $fields->addFieldToTab(
            //         'Root.Settings',
            //         DropdownField::create(
            //             'Template',
            //             'Template',
            //             $availableTemplates
            //         )
            //     );
            // }

            // add a dropdown with available colors
            $fields->removeByName('BackgroundColor');
            if (count($this->getBackgroundColors())) {
                $fields->addFieldToTab(
                    'Root.Settings',
                    DropdownField::create(
                        'BackgroundColor',
                        'Background Color',
                        $this->getBackgroundColors()
                    ),
                    'ExtraClass'
                );
            }


            // Add additional fields for setting an Image Background
            $useImage = static::config()->get('allow_image_background');
            $fields->removeByName([
                'TextColor',
                'BGImage'
            ]);
            if ($useImage) {
                // add an color field for Background

                $fields->addFieldToTab(
                    'Root.Settings',
                    $textColor = DropdownField::create(
                        'TextColor',
                        'Text Color',
                        $this->getTextColors()
                    ),
                    'ExtraClass'
                );
                $textColor->hideUnless('BackgroundColor')->isEqualTo('_image');

                // add an image field for Background
                $fields->addFieldToTab(
                    'Root.Settings',
                    $backgroundImage = UploadField::create(
                        'BGImage',
                        'Background Image'
                    )
                        ->setFolderName('Uploads/Elements/Backgrounds')
                        ->setIsMultiUpload(false),
                    'ExtraClass'
                );
                $backgroundImage->hideUnless('BackgroundColor')->isEqualTo('_image');
            }
        });



        return parent::getCMSFields();
    }

    /**
     * getBackgroundColors
     *
     * @return array
     */
    public function getBackgroundColors()
    {
        $colors = static::config()->get('background_colors');
        $useImage = static::config()->get('allow_image_background');
        $selection = [];
        foreach ($colors as $colorKey => $colorName) {
            $selection[$colorKey] = _t(
                __CLASS__ . '.' . $colorKey,
                $colorName
            );
        }

        if ($useImage) {
            $selection['_image'] = _t(
                __CLASS__ . '._image',
                'Image background'
            );
        }
        return $selection;
    }

    /**
     * getTextColors
     *
     * @return array
     */
    public function getTextColors()
    {
        $colors = static::config()->get('text_colors');
        $selection = [];
        foreach ($colors as $colorKey => $colorName) {
            $selection[$colorKey] = _t(
                __CLASS__ . '.' . $colorKey,
                $colorName
            );
        }
        return $selection;
    }

    // /**
    //  * getAvailableTemplates
    //  *
    //  * @return array
    //  */
    // public function getAvailableTemplates()
    // {
    //     $templates = static::config()->get('available_templates');
    //     foreach ($templates as $templateKey => $templateName) {
    //         $selection[$templateKey] = _t(
    //             __CLASS__ . '.' . $templateKey,
    //             $templateName
    //         );
    //     }
    //     return $selection;
    // }

    /**
     * getBackgroundColor - returns the background Color
     *
     * @return string|null
     */
    public function getComputedBackgroundColor()
    {
        $availableColors = static::config()->get('background_colors');
        if (
            $this->BackgroundColor &&
            isset($availableColors[$this->BackgroundColor]) &&
            $this->BackgroundColor !== '_image' &&
            $this->BackgroundColor !== 'default'
        ) {
            return $this->BackgroundColor;
        }
        return null;
    }

    /**
     * getTextColor - returns the current text color
     *
     * @return string|null
     */
    public function getComputedTextColor()
    {
        $availableColors = static::config()->get('text_colors');
        $bgToTextColor = static::config()->get('text_colors_by_background');
        if (
            $this->TextColor &&
            isset($availableColors[$this->TextColor]) &&
            $this->BackgroundColor === '_image'
        ) {
            return $this->TextColor;
        } elseif (
            $this->BackgroundColor &&
            $this->BackgroundColor !== 'default' &&
            $this->BackgroundColor !== '_image' &&
            isset($bgToTextColor[$this->BackgroundColor])
        ) {
            return $bgToTextColor[$this->BackgroundColor];
        }
        return null;
    }

    /**
     * getBackgroundImage - returns the durrent background image
     *
     * @return Image|null
     */
    public function getComputedBackgroundImage()
    {
        if (
            static::config()->get('allow_image_background') &&
            $this->BackgroundColor === '_image' &&
            $this->BGImageID != 0
        ) {
            return $this->BGImage;
        }
        return null;
    }

    /**
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
