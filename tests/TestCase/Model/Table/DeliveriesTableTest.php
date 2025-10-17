<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DeliveriesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DeliveriesTable Test Case
 */
class DeliveriesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\DeliveriesTable
     */
    protected $Deliveries;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.Deliveries',
        'app.Customers',
        'app.MillingQueue',
        'app.MillingTransactions',
        'app.Payments',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Deliveries') ? [] : ['className' => DeliveriesTable::class];
        $this->Deliveries = $this->getTableLocator()->get('Deliveries', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Deliveries);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\DeliveriesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @link \App\Model\Table\DeliveriesTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
