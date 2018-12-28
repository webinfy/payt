<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class MerchantPaymentGatewaysTable extends Table {

    public function initialize(array $config) {
        parent::initialize($config);

        $this->addBehavior('Timestamp');

        $this->belongsTo('Merchants', [
            'foreignKey' => FALSE,
            'conditions' => ['Merchants.user_id=merchant_id'],
            'joinType' => 'LEFT'
        ]);
        $this->belongsTo('PaymentGateways', [
            'foreignKey' => 'payment_gateway_id',
            'joinType' => 'LEFT'
        ]);
    }

    public function validationDefault(Validator $validator) {

        $validator
                ->requirePresence('title', 'create', 'Name required!!')
                ->notEmpty('title', 'Name should not be empty!!');

        $validator
                ->requirePresence('payment_gateway_id', 'create', 'Select Payment Gateway!!')
                ->notEmpty('payment_gateway_id', 'Payment Gateway should not be empty!!');

        $validator
                ->requirePresence('merchant_key', 'create', 'Merchant Key Required!!')
                ->notEmpty('merchant_key', 'Merchant Key should not be empty!!');

        $validator
                ->requirePresence('merchant_salt', 'create', 'Merchant Salt required!!')
                ->notEmpty('merchant_salt', 'Merchant Salt should not be empty!!');

        return $validator;
    }

}
