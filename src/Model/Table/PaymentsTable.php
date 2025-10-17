<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class PaymentsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('payments');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        // Changed from Customers to Users
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);
        
        $this->belongsTo('MillingOrders', [
            'foreignKey' => 'milling_order_id',
            'joinType' => 'LEFT',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('user_id') // Changed from customer_id to user_id
            ->requirePresence('user_id', 'create') // Changed from customer_id to user_id
            ->notEmptyString('user_id', 'User is required'); // Changed from customer_id to user_id

        $validator
            ->integer('milling_order_id')
            ->allowEmptyString('milling_order_id');

        $validator
            ->decimal('amount')
            ->requirePresence('amount', 'create')
            ->notEmptyString('amount', 'Amount is required')
            ->add('amount', 'validNumber', [
                'rule' => function ($value) {
                    return $value > 0;
                },
                'message' => 'Amount must be greater than 0'
            ]);

        $validator
            ->scalar('payment_method')
            ->maxLength('payment_method', 20)
            ->requirePresence('payment_method', 'create')
            ->notEmptyString('payment_method', 'Payment method is required');

        $validator
            ->dateTime('payment_date')
            ->allowEmptyDateTime('payment_date');

        $validator
            ->scalar('notes')
            ->allowEmptyString('notes');

        return $validator;
    }

    public function buildRules(\Cake\ORM\RulesChecker $rules): \Cake\ORM\RulesChecker
    {
        $rules->add($rules->existsIn('user_id', 'Users'), [
            'message' => 'User does not exist'
        ]);
        
        $rules->add($rules->existsIn('milling_order_id', 'MillingOrders'), [
            'message' => 'Milling order does not exist',
            'allowNullable' => true
        ]);

        return $rules;
    }
}