<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class SubMerchantsTable extends Table {

    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('sub_merchants');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Merchant', [
            'className' => 'Users',
            'foreignKey' => 'merchant_id',
            'joinType' => 'INNER'
        ]);
    }

    public function validationDefault(Validator $validator) {

        $validator
                ->requirePresence('merchant_id', 'create', 'Please select Merchant!!')
                ->notEmpty('merchant_id', 'Please select Merchant!!');

        $validator
                ->requirePresence('name', 'create', 'Name is Required!!')
                ->notEmpty('name', 'Name should not be empty!!');

        $validator
                ->requirePresence('email', 'create', 'Name is Required!!')
                ->notEmpty('email', 'Email should not be empty!!');

        $validator
                ->requirePresence('payumid', 'create', 'Name is Required!!')
                ->notEmpty('payumid', 'PAYMID should not be empty!!');

        return $validator;
    }

    public function buildRules(RulesChecker $rules) {
        $rules->add($rules->existsIn(['merchant_id'], 'Merchant'));
        return $rules;
    }

}
