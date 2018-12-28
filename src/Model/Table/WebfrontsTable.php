<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class WebfrontsTable extends Table {

    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('webfronts');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'merchant_id',
            'joinType' => 'INNER'
        ]);
                
        $this->belongsTo('Merchants', [
            'className' => 'Merchants',
            'foreignKey' => FALSE,
            'conditions' => ['Merchants.user_id=Webfronts.merchant_id'],
            'joinType' => 'LEFT'
        ]);

        $this->belongsTo('UploadedPaymentFiles', [
            'foreignKey' => 'id',
            'joinType' => 'LEFT'
        ]);

        $this->hasMany('Payments', [
            'foreignKey' => 'webfront_id',
            'dependent' => TRUE
        ]);

        $this->hasMany('WebfrontFields', [
            'foreignKey' => 'webfront_id',
            'dependent' => TRUE
        ]);
        
        $this->hasMany('WebfrontPaymentAttributes', [
            'foreignKey' => 'webfront_id',
            'dependent' => TRUE
        ]);
    }

    public function validationDefault(Validator $validator) {

        $validator
                ->requirePresence('url', 'create', "Please enter URL!!")
                ->notEmpty('url', 'URL is Required!!');

        $validator
                ->email('email', false, 'Email must be valid!!')
                ->requirePresence('email', 'create', 'Please enter email!!')
                ->notEmpty('email', "Email is Required!!");

        $validator
                ->requirePresence('phone', 'create', 'Phone canont be empty!!')
                ->notEmpty('phone', "Phone is Required!!");

        $validator
                ->requirePresence('address', 'create', 'Address canont be empty!!')
                ->notEmpty('address', "Address is Required!!");

        $validator
                ->requirePresence('title', 'create', 'Title cannot be empty!!')
                ->notEmpty('title', "Title is Required!!");

        $validator
                ->requirePresence('description', 'create', "Please enter description!!")
                ->notEmpty('description', "Description is Required!!");
        
        $validator
                ->allowEmpty('late_fee_type')
                ->inList('late_fee_type', [1, 2, 3], "Invalid Late fee type!!");
        
        $validator
                ->allowEmpty('late_fee_amount')
                ->numeric('late_fee_amount', "Late Fee Amount should be numeric!!");
        
        $validator
                ->allowEmpty('recurring_period')
                ->numeric('recurring_period', "Recurring Period should be an number!!");
        
        $validator
                ->allowEmpty('recurring_period')
                ->integer('recurring_period', "Recurring Period should be an number!!");
        
        $validator
                ->allowEmpty('periodic_days_1')
                ->integer('periodic_days_1', "Period 1 must be a number!!");
        
        $validator
                ->allowEmpty('periodic_amount_1')
                ->numeric('periodic_amount_1', "Period 1 Amount must be numeric!!");
        
        $validator
                ->allowEmpty('periodic_days_2')
                ->integer('periodic_days_2', "Period 2 must be a number!!");
        
        $validator
                ->allowEmpty('periodic_amount_2')
                ->numeric('periodic_amount_2', "Period 2 Amount must be numeric!!");                


        return $validator;
    }

    public function buildRules(RulesChecker $rules) {
        $rules->add($rules->isUnique(['url'], "URL Already Exist!!"));
        $rules->add($rules->existsIn(['merchant_id'], 'Users'));
        return $rules;
    }

}
