<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Delivery Entity
 *
 * @property int $id
 * @property int $customer_id
 * @property string $weight
 * @property string|null $status
 * @property \Cake\I18n\Date|null $delivery_date
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\Customer $customer
 * @property \App\Model\Entity\MillingQueue[] $milling_queue
 * @property \App\Model\Entity\MillingTransaction[] $milling_transactions
 * @property \App\Model\Entity\Payment[] $payments
 */
class Delivery extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'customer_id' => true,
        'weight' => true,
        'status' => true,
        'delivery_date' => true,
        'created' => true,
        'modified' => true,
        'customer' => true,
        'milling_queue' => true,
        'milling_transactions' => true,
        'payments' => true,
    ];
}
