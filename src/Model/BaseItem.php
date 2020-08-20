<?php

namespace Syntro\SilverStripeElementalBaseitems\Model;

use SilverStripe\Assets\Image;
use SilverStripe\ORM\DataObject;
use SilverStripe\Versioned\Versioned;
use SilverStripe\Security\Permission;
use SilverStripe\Control\Director;
use gorriecoe\Link\Models\Link;
use gorriecoe\LinkField\LinkField;
use BucklesHusky\FontAwesomeIconPicker\Forms\FAPickerField;
use DNADesign\Elemental\Forms\TextCheckboxGroupField;


/**
 * @author Matthias Leutenegger <hello@syntro.ch>
 */
class BaseItem extends DataObject
{

    /**
     * @var array
     */
    private static $db = [
        'Title' => 'Varchar',
        'ShowIcon' => 'Boolean',
        'FAIcon' => 'Varchar(20)',
        'ShowTitle' => 'Varchar',
        'Content' => 'HTMLText'
    ];

    /**
     * @var array
     */
    private static $has_one = [
        'Link' => Link::class,
        'Image' => Image::class
    ];

    /**
     * @var array
     */
    private static $searchable_fields = array(
        'Title',
        'Content',
    );

    /**
     * @var array
     */
    private static $extensions = [
        Versioned::class,
    ];

    /**
     * Adds Publish button.
     *
     * @var bool
     */
    private static $versioned_gridfield_extensions = true;

    /**
     * @var string
     */
    private static $table_name = 'BaseElementItem';

    /**
     * @return FieldList
     */
    public function getCMSFields()
    {
        $this->beforeUpdateCMSFields(function ($fields) {
            /** @var FieldList $fields */
            $fields->removeByName([
                'Sort',
            ]);

            // Add a combined Title/ShowTitle field to achieve elemental look
            $fields->removeByName('ShowTitle');
            $fields->replaceField(
                'Title',
                TextCheckboxGroupField::create()
                    ->setName($this->fieldLabel('Title'))
            );

            // Use Link fields to render Link
            $fields->replaceField(
                'LinkID',
                $link = LinkField::create(
                    'Link',
                    'Link',
                    $this
                )
            );
            $link->setDescription('Add a call to action.');

            // use FAPickerField for Icon
            $fields->replaceField(
                'FAIcon',
                $faPicker = FAPickerField::create('FAIcon', 'Icon')
            );
            $faPicker->hideUnless("ShowIcon")->isChecked();
        });
        return parent::getCMSFields();
    }


    /**
     * @return SiteTree|null
     */
    public function getPage()
    {
        $page = Director::get_current_page();
        // because $page can be a SiteTree or Controller
        return $page instanceof SiteTree ? $page : null;
    }

    /**
     * Basic permissions, defaults to page perms where possible.
     *
     * @param \SilverStripe\Security\Member|null $member
     * @return boolean
     */
    public function canView($member = null)
    {
        $extended = $this->extendedCan(__FUNCTION__, $member);
        if ($extended !== null) {
            return $extended;
        }

        if ($page = $this->getPage()) {
            return $page->canView($member);
        }

        return Permission::check('CMS_ACCESS', 'any', $member);
    }

    /**
     * Basic permissions, defaults to page perms where possible.
     *
     * @param \SilverStripe\Security\Member|null $member
     *
     * @return boolean
     */
    public function canEdit($member = null)
    {
        $extended = $this->extendedCan(__FUNCTION__, $member);
        if ($extended !== null) {
            return $extended;
        }

        if ($page = $this->getPage()) {
            return $page->canEdit($member);
        }

        return Permission::check('CMS_ACCESS', 'any', $member);
    }

    /**
     * Basic permissions, defaults to page perms where possible.
     *
     * Uses archive not delete so that current stage is respected i.e if a
     * element is not published, then it can be deleted by someone who doesn't
     * have publishing permissions.
     *
     * @param \SilverStripe\Security\Member|null $member
     *
     * @return boolean
     */
    public function canDelete($member = null)
    {
        $extended = $this->extendedCan(__FUNCTION__, $member);
        if ($extended !== null) {
            return $extended;
        }

        if ($page = $this->getPage()) {
            return $page->canArchive($member);
        }

        return Permission::check('CMS_ACCESS', 'any', $member);
    }

    /**
     * Basic permissions, defaults to page perms where possible.
     *
     * @param \SilverStripe\Security\Member|null $member
     * @param array $context
     *
     * @return boolean
     */
    public function canCreate($member = null, $context = array())
    {
        $extended = $this->extendedCan(__FUNCTION__, $member);
        if ($extended !== null) {
            return $extended;
        }

        return Permission::check('CMS_ACCESS', 'any', $member);
    }

}
