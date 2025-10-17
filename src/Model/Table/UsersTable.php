<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\EventInterface;
use ArrayObject;

class UsersTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('users');
        $this->setDisplayField('username');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('MillingOrders', [
            'foreignKey' => 'user_id',
        ]);
        $this->hasMany('Payments', [
            'foreignKey' => 'user_id',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->notEmptyString('username', 'A username is required')
            ->minLength('username', 3, 'Username must be at least 3 characters long')
            ->add('username', 'unique', [
                'rule' => 'validateUnique',
                'provider' => 'table',
                'message' => 'Username already exists'
            ])
            ->notEmptyString('password', 'A password is required', 'create')
            ->minLength('password', 6, 'Password must be at least 6 characters long', 'create')
            ->notEmptyString('role', 'A role is required')
            ->inList('role', ['admin', 'staff', 'customer', 'owner'], 'Please select a valid role')
            ->allowEmptyString('first_name')
            ->allowEmptyString('last_name')
            ->email('email', false, 'Please provide a valid email')
            ->allowEmptyString('phone')
            ->allowEmptyString('address');

        return $validator;
    }

    // Automatically hash passwords before saving
    public function beforeSave(EventInterface $event, $entity, ArrayObject $options)
    {
        if ($entity->isDirty('password') && !empty($entity->password)) {
            $entity->password = password_hash($entity->password, PASSWORD_DEFAULT);
        }
        return true;
    }
}