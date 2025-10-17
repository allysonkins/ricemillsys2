<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * MillingTransactionsFixture
 */
class MillingTransactionsFixture extends TestFixture
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
                'delivery_id' => 1,
                'input_weight' => 1.5,
                'output_rice_weight' => 1.5,
                'output_darak_weight' => 1.5,
                'output_ipa_weight' => 1.5,
                'milling_date' => '2025-09-28 14:27:57',
                'created' => '2025-09-28 14:27:57',
                'modified' => '2025-09-28 14:27:57',
            ],
        ];
        parent::init();
    }
}
