<?php

namespace Inboxly\Receiver\Tests\Managers;

use Inboxly\Receiver\Contracts\QueryExplorer;
use Inboxly\Receiver\Contracts\UrlExplorer;
use Inboxly\Receiver\Managers\ExplorerManager;
use Inboxly\Receiver\Tests\Stubs\StubQueryExplorer;
use Inboxly\Receiver\Tests\Stubs\StubUrlExplorer;
use Inboxly\Receiver\Tests\Stubs\StubUrlQueryExplorer;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class ExplorerManagerTest extends TestCase
{
    /**
     * @see ExplorerManager::addExplorer()
     * @test
     */
    public function testAddExplorer()
    {
        // Setup
        $manager = $this->makeManagerWithStubExplorers();
        $reflection = new ReflectionClass(ExplorerManager::class);

        // Asserts
        $urlExplorersProperty = $reflection->getProperty('urlExplorers');
        $urlExplorersProperty->setAccessible(true);
        $urlExplorers = $urlExplorersProperty->getValue($manager);

        $this->assertCount(2, $urlExplorers);
        $this->assertInstanceOf(UrlExplorer::class, $urlExplorers['stub-url']);
        $this->assertInstanceOf(UrlExplorer::class, $urlExplorers['stub-url-query']);

        $queryExplorersProperty = $reflection->getProperty('queryExplorers');
        $queryExplorersProperty->setAccessible(true);
        $queryExplorers = $queryExplorersProperty->getValue($manager);

        $this->assertCount(2, $queryExplorers);
        $this->assertInstanceOf(QueryExplorer::class, $queryExplorers['stub-query']);
        $this->assertInstanceOf(QueryExplorer::class, $queryExplorers['stub-url-query']);
    }

    /**
     * @see ExplorerManager::explore()
     * @test
     */
    public function testExplore()
    {
        $this->markTestSkipped();
    }

    /**
     * @return \Inboxly\Receiver\Managers\ExplorerManager
     */
    private function makeManagerWithStubExplorers(): ExplorerManager
    {
        $manager = new ExplorerManager();
        $manager->addExplorer(new StubUrlExplorer());
        $manager->addExplorer(new StubQueryExplorer());
        $manager->addExplorer(new StubUrlQueryExplorer());

        return $manager;
    }
}
