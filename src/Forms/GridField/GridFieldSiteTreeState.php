<?php

namespace SilverStripe\Lumberjack\Forms\GridField;

use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridField_ColumnProvider;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\FieldType\DBDatetime;

/**
 * Provides a component to the {@link GridField} which shows the publish status of a page.
 *
 * @package silverstripe
 * @subpackage lumberjack
 *
 * @author Michael Strong <mstrong@silverstripe.org>
 * @author Reece Alexander <reece@steadlane.com.au>
 **/
class GridFieldSiteTreeState implements GridField_ColumnProvider
{
    /**
     * {@inheritdoc}
     *
     * @param GridField $gridField
     * @param array $columns
     */
    public function augmentColumns($gridField, &$columns)
    {
        // Ensure Actions always appears as the last column.
        $key = array_search("Actions", $columns);
        if ($key !== false) {
            unset($columns[$key]);
        }

        $columns = array_merge($columns, [
            "State",
            "Actions",
        ]);
    }

    /**
     * {@inheritdoc}
     *
     * @param GridField $gridField
     * @return array
     */
    public function getColumnsHandled($gridField)
    {
        return ["State"];
    }

    /**
     * {@inheritdoc}
     *
     * @param GridField $gridField
     * @param Page $record
     * @param string $columnName
     * @return string
     */
    public function getColumnContent($gridField, $record, $columnName)
    {
        if ($columnName == "State") {
            if ($record->hasMethod("isPublished")) {
                $modifiedLabel = "";
                if ($record->isModifiedOnStage) {
                    $modifiedLabel = "<span class='modified'>" . _t("GridFieldSiteTreeState.Modified", "Modified") . "</span>";
                }

                $published = $record->isPublished();

                /** @var DBDatetime $lastEdited */
                $lastEdited = $record->dbObject("LastEdited");

                if (!$published) {
                    return _t(
                        "GridFieldSiteTreeState.Draft",
                        '<i class="btn-icon gridfield-icon btn-icon-pencil"></i> Saved as Draft on {date}',
                        "State for when a post is saved.",
                        [
                            "date" => $lastEdited->Nice()
                        ]
                    );
                } else {
                    $output = _t(
                        "GridFieldSiteTreeState.Published",
                        '<i class="btn-icon gridfield-icon btn-icon-accept"></i> Published on {date}',
                        "State for when a post is published.",
                        [
                            "date" => $lastEdited->Nice()
                        ]
                    );

                    return $output . $modifiedLabel;
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     *
     * @param GridField $gridField
     * @param DataObject $record
     * @param string $columnName
     * @return array
     */
    public function getColumnAttributes($gridField, $record, $columnName)
    {
        if ($columnName == "State") {
            if ($record->hasMethod("isPublished")) {
                $published = $record->isPublished();
                if (!$published) {
                    $class = "gridfield-icon draft";
                } else {
                    $class = "gridfield-icon published";
                }
                return ["class" => $class];
            }
        }
        return [];
    }

    /**
     * @param GridField $gridField
     * @param string $columnName
     * @return array
     */
    public function getColumnMetaData($gridField, $columnName)
    {
        switch ($columnName) {
            case 'State':
                return ["title" => _t("GridFieldSiteTreeState.StateTitle", "State", "Column title for state")];
        }
    }

}
