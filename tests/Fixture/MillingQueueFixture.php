<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * MillingQueueFixture
 */
class MillingQueueFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public string $table = 'milling_queue';
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
                'queue_number' => 1,
                'status' => 'Lorem ipsum dolor sit amet',
                'start_time' => '2025-09-28 14:27:12',
                'end_time' => '2025-09-28 14:27:12',
                'created' => '2025-09-28 14:27:12',
                'modified' => '2025-09-28 14:27:12',
            ],
        ];
        parent::init();
    }
}
