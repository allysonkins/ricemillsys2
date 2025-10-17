<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class MillingOrdersTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('milling_orders');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);

        $this->hasMany('Payments', [
            'foreignKey' => 'milling_order_id',
            'dependent' => true,
            'cascadeCallbacks' => true,
        ]);
    }

    public function validationDefault(Validator $validator): Validator
{
    $validator
        ->integer('user_id')
        ->requirePresence('user_id', 'create')
        ->notEmptyString('user_id', 'User is required');

    $validator
        ->numeric('weight') // Changed from decimal to numeric
        ->requirePresence('weight', 'create')
        ->notEmptyString('weight', 'Weight is required')
        ->add('weight', 'validWeight', [
            'rule' => function ($value, $context) {
                return $value > 0;
            },
            'message' => 'Weight must be greater than 0'
        ]);

    $validator
        ->numeric('total_amount') // Changed from decimal to numeric
        ->requirePresence('total_amount', 'create')
        ->notEmptyString('total_amount', 'Total amount is required')
        ->add('total_amount', 'validAmount', [
            'rule' => function ($value, $context) {
                return $value >= 0;
            },
            'message' => 'Total amount cannot be negative'
        ]);

    // Make all other fields optional for now
    $validator
        ->scalar('status')
        ->allowEmptyString('status');

    $validator
        ->scalar('delivery_option')
        ->allowEmptyString('delivery_option');

    $validator
        ->scalar('delivery_status')
        ->allowEmptyString('delivery_status');

    $validator
        ->date('delivery_date')
        ->allowEmptyDate('delivery_date');

    $validator
        ->scalar('payment_status')
        ->allowEmptyString('payment_status');

    return $validator;
}
}