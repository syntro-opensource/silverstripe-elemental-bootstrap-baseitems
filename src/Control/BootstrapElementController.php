<?php
namespace Syntro\SilverStripeElementalBaseitems\Control;

use SilverStripe\View\Requirements;
use DNADesign\Elemental\Controllers\ElementController;
use Syntro\SilverStripeElementalBaseitems\Elements\BootstrapSectionBaseElement;


/**
 * Intermediate element controller to handle Templating to make it more
 * in-line with the general collection.
 *
 * @author Matthias Leutenegger <hello@syntro.ch>
 */
class BootstrapElementController extends ElementController
{

    /**
     * Location of Templates
     *
     * @config
     * @var string
     */
    private static $template_root = 'Syntro\\BootstrapElemental\\';

    /**
     * Renders the managed {@link BaseElement} wrapped with the current
     * {@link ElementController}.
     *
     * @return string HTML
     */
    public function forTemplate()
    {
        $defaultStyles = $this->config()->get('default_styles');
        if ($this->config()->get('include_default_styles') && !empty($defaultStyles)) {
            foreach ($defaultStyles as $stylePath) {
                Requirements::css($stylePath);
            }
        }

        $template = $this->element->config()->get('controller_template');
        $templateRoot = static::config()->get('template_root');

        return $this->renderWith([
            'type' => 'Layout',
            $templateRoot.$template
        ]);
    }
}
