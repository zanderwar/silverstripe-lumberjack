<?php

namespace SilverStripe\Lumberjack\Forms;

use SilverStripe\Forms\GridField\GridFieldButtonRow;
use SilverStripe\Forms\GridField\GridFieldConfig;
use SilverStripe\Forms\GridField\GridFieldDataColumns;
use SilverStripe\Forms\GridField\GridFieldFilterHeader;
use SilverStripe\Forms\GridField\GridFieldPageCount;
use SilverStripe\Forms\GridField\GridFieldPaginator;
use SilverStripe\Forms\GridField\GridFieldSortableHeader;
use SilverStripe\Forms\GridField\GridFieldToolbarHeader;
use SilverStripe\Lumberjack\Forms\GridFieldSiteTreeAddNewButton;
use SilverStripe\Lumberjack\Forms\GridFieldSiteTreeEditButton;
use SilverStripe\Lumberjack\Forms\GridFieldSiteTreeState;

/**
 * GridField config necessary for managing a SiteTree object.
 *
 * @package silverstripe
 * @subpackage lumberjack
 *
 * @author Michael Strong <mstrong@silverstripe.org>
 **/
class GridFieldConfig_Lumberjack extends GridFieldConfig
{
    /**
     * @param int|null $itemsPerPage
     */
    public function __construct($itemsPerPage = null)
    {
        parent::__construct($itemsPerPage);

        $this->addComponent(GridFieldButtonRow::create('before'));
        $this->addComponent(GridFieldSiteTreeAddNewButton::create('buttons-before-left'));
        $this->addComponent(GridFieldToolbarHeader::create());
        $this->addComponent(GridFieldSortableHeader::create());
        $this->addComponent(GridFieldFilterHeader::create());
        $this->addComponent(GridFieldDataColumns::create());
        $this->addComponent(GridFieldSiteTreeEditButton::create());
        $this->addComponent(GridFieldPageCount::create('toolbar-header-right'));
        $this->addComponent($pagination = GridFieldPaginator::create($itemsPerPage));
        $this->addComponent(GridFieldSiteTreeState::create());

        $pagination->setThrowExceptionOnBadDataType(true);
    }
}
