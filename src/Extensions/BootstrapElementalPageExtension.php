<?php

namespace Syntro\SilverStripeElementalBaseitems\Extensions;

use SilverStripe\ORM\DataExtension;
use DNADesign\Elemental\Extensions\ElementalPageExtension;
use DNADesign\Elemental\Models\ElementContent;
use Syntro\SilverStripeElementalBaseitems\Elements\BootstrapSectionBaseElement;



/**
 * Extension to remove the base Field from the List of available elements
 *
 * @author Mathias Leutenegger <hello@syntro.ch>
 */
class BootstrapElementalPageExtension extends ElementalPageExtension
{
    public function getElementalTypes()
    {
        $list = parent::getElementalTypes();
        if (isset($list[BootstrapSectionBaseElement::class])) {
            unset($list[BootstrapSectionBaseElement::class]);
        }
        // We have to remove the original content block as it does not support
        // settings like background or colors
        if (isset($list[ElementContent::class])) {
            unset($list[ElementContent::class]);
        }
        return $list;
    }
}
