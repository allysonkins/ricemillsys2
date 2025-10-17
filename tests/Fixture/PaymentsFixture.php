<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * PaymentsFixture
 */
class PaymentsFixture extends TestFixture
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
                'delivery_id' => 1,
                'amount' => 1.5,
                'payment_type' => 'Lorem ipsum dolor sit amet',
                'payment_method' => 'Lorem ipsum dolor sit amet',
                'payment_date' => '2025-09-28 14:28:09',
                'notes' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'created' => '2025-09-28 14:28:09',
                'modified' => '2025-09-28 14:28:09',
            ],
        ];
        parent::init();
    }
}
