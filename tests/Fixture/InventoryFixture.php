<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * InventoryFixture
 */
class InventoryFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public string $table = 'inventory';
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'product_id' => 1,
                'quantity' => 1.5,
                'last_updated' => '2025-09-28 14:28:21',
                'created' => '2025-09-28 14:28:21',
                'modified' => '2025-09-28 14:28:21',
            ],
        ];
        parent::init();
    }
}
