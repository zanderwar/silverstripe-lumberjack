<?php

namespace SilverStripe\Lumberjack\Tests;

use SilverStripe\Lumberjack\Extensions\Lumberjack;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Core\Config\Config;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\Dev\TestOnly;

class LumberjackTest extends SapphireTest
{
    /** @var string */
    protected static $fixture_file = 'fixtures.yml';

    /**
     * @var array
     */
    protected $extraDataObjects = array(
        SiteTree_Lumberjack::class,
        SiteTree_LumberjackHidden::class,
        SiteTree_LumberjackShown::class,
    );

    /**
     * @covers Lumberjack
     */
    public function testGetExcludedSiteTreeClassNames()
    {
        /** @var Lumberjack $standard */
        $standard = $this->objFromFixture(self::class, 'standard');

        $excluded = $standard->getExcludedSiteTreeClassNames();
        $excluded = $this->filteredClassNames($excluded, $this->extraDataObjects);
        $this->assertEquals($excluded, array('SiteTree_LumberjackHidden' => 'SiteTree_LumberjackHidden'));

        Config::modify()->set('SiteTree', 'show_in_sitetree', false);

        $excluded = $standard->getExcludedSiteTreeClassNames();
        $excluded = $this->filteredClassNames($excluded, $this->extraDataObjects);

        $this->assertEquals($excluded, array(
            SiteTree_Lumberjack::class => SiteTree_Lumberjack::class,
            SiteTree_LumberjackHidden::class => SiteTree_LumberjackHidden::class
        ));

    }

    /**
     * Because we don't know what other test classes are included, we filter to the ones we know
     * and want to test.
     *
     * @param array $classNames
     * @param array $explicitClassNames
     *
     * @return array
     */
    protected function filteredClassNames($classNames, $explicitClassNames)
    {
        $classNames = array_filter($classNames, function ($value) use ($explicitClassNames) {
            return in_array($value, $explicitClassNames);
        });

        return $classNames;
    }

}

/**
 * Class SiteTree_Lumberjack
 *
 * @package silverstripe
 * @subpackage lumberjack-tests
 */
class SiteTree_Lumberjack extends SiteTree implements TestOnly
{
    private static $extensions = array(
        Lumberjack::class,
    );
}

/**
 * Class SiteTree_LumberjackHidden
 *
 * @package silverstripe
 * @subpackage lumberjack-tests
 */
class SiteTree_LumberjackHidden extends SiteTree_Lumberjack implements TestOnly
{
    private static $show_in_sitetree = false;
}

/**
 * Class SiteTree_LumberjackShown
 *
 * @package silverstripe
 * @subpackage lumberjack-tests
 */
class SiteTree_LumberjackShown extends SiteTree_LumberjackHidden implements TestOnly
{
    private static $show_in_sitetree = true;
}