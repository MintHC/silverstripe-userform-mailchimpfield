<?php

namespace Swordfox\UserForms;

use SilverStripe\ORM\DataObject;
use SilverStripe\Security\Member;
use SilverStripe\Versioned\Versioned;

use SilverStripe\UserForms\Model\EditableFormField;
use Swordfox\UserForms\EditableMailChimpField;

/**
 * EditableMailChimpField > EditableMergeField
 *
 * @package Swordfox\UserForms
 * @property int $FieldID
 * @property int $ParentID
 * @property int $Sort
 * @property string $Value
 * @mixin Versioned
 * @method EditableMailChimpField Parent()
 */
class EditableMergeField extends DataObject
{
    private static $default_sort = 'Sort';

    private static $db = [
        'Sort' => 'Int',
        'Value' => 'Varchar(255)',
    ];

    private static $has_one = [
        'Parent' => EditableMailChimpField::class,
        'Field' => EditableFormField::class,
    ];

    private static $extensions = [
        Versioned::class . "('Stage', 'Live')",
    ];

    private static $summary_fields = [
        'Field.Name',
        'Value',
    ];

    private static $table_name = 'EditableMergeField';

    /**
     * Fetches a value for $this->Value. If empty values are not allowed,
     * then this will return the title in the case of an empty value.
     *
     * @return string
     */
    public function getValue()
    {
        $value = $this->getField('Value');
        if (empty($value) and !empty($this->Field()->Title)) {
            return strtoupper(str_replace(' ', '', $this->Field()->Title));
        }
        return $value;
    }

    protected function onBeforeWrite()
    {
        if (!$this->Sort) {
            $this->Sort = EditableOption::get()->max('Sort') + 1;
        }

        if (!$this->Value) {
            $this->Value = strtoupper(str_replace(' ', '', $this->Field()->Title));
        }

        parent::onBeforeWrite();
    }
    /**
     * @param Member $member
     * @return boolean
     */
    public function canEdit($member = null)
    {
        return $this->Parent()->canEdit($member);
    }
    /**
     * @param Member $member
     * @return boolean
     */
    public function canDelete($member = null)
    {
        return $this->canEdit($member);
    }

    /**
     * @param Member $member
     * @return bool
     */
    public function canView($member = null)
    {
        return $this->Parent()->canView($member);
    }

    /**
     * Return whether a user can create an object of this type
     *
     * @param Member $member
     * @param array $context Virtual parameter to allow context to be passed in to check
     * @return bool
     */
    public function canCreate($member = null, $context = [])
    {
        // Check parent object
        $parent = $this->Parent();
        if ($parent) {
            return $parent->canCreate($member);
        }

        // Fall back to secure admin permissions
        return parent::canCreate($member);
    }

    /**
     * @param Member $member
     * @return bool
     */
    public function canPublish($member = null)
    {
        return $this->canEdit($member);
    }
    /**
     * @param Member $member
     * @return bool
     */
    public function canUnpublish($member = null)
    {
        return $this->canDelete($member);
    }
}
