<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class MerchantsTable extends Table {

    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('merchants');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Webfronts', [
            'foreignKey' => 'merchant_id',
            'joinType' => 'INNER'
        ]);
         $this->belongsTo('MerchantPaymentGateways', [
            'className' => 'MerchantPaymentGateways',
            'foreignKey' => FALSE,
            'conditions' => ['Merchants.user_id=MerchantPaymentGateways.merchant_id'],
            'joinType' => 'LEFT'
        ]);
    }

    public function beforeDelete($event, $entity, $options) {
        if (!empty($entity->logo) && file_exists(HTTP_ROOT . MERCHANT_LOGO . $entity->logo)) {
            @unlink(HTTP_ROOT . MERCHANT_LOGO . $entity->logo);
        }
    }

    public function validationDefault(Validator $validator) {

        $validator
                ->scalar('website')
                ->maxLength('website', 100, 'Website url length should not exceed 100!!')
                ->allowEmpty('website');

        $validator
                ->scalar('facebook_url')
                ->maxLength('facebook_url', 256, 'Twitter url length should not exceed 256!!')
                ->allowEmpty('facebook_url');

        $validator
                ->scalar('twitter_url')
                ->maxLength('twitter_url', 256, 'Facebook url length should not exceed 256!!')
                ->allowEmpty('twitter_url');

        return $validator;
    }

    public function buildRules(RulesChecker $rules) {
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        return $rules;
    }

}
