<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\MillingQueueTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\MillingQueueTable Test Case
 */
class MillingQueueTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\MillingQueueTable
     */
    protected $MillingQueue;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.MillingQueue',
        'app.Deliveries',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('MillingQueue') ? [] : ['className' => MillingQueueTable::class];
        $this->MillingQueue = $this->getTableLocator()->get('MillingQueue', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->MillingQueue);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\MillingQueueTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @link \App\Model\Table\MillingQueueTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
