<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * DeliveriesFixture
 */
class DeliveriesFixture extends TestFixture
{
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
                'customer_id' => 1,
                'weight' => 1.5,
                'status' => 'Lorem ipsum dolor sit amet',
                'delivery_date' => '2025-09-28',
                'created' => '2025-09-28 14:27:03',
                'modified' => '2025-09-28 14:27:03',
            ],
        ];
        parent::init();
    }
}
