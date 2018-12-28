<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class AdminSettingsTable extends Table {

    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('admin_settings');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        
    }

    public function validationDefault(Validator $validator) {
        $validator
                ->integer('id')
                ->allowEmpty('id', 'create');

        $validator
                ->requirePresence('site_name', 'create')
                ->notEmpty('site_name');

        $validator
                ->notEmpty('admin_email', 'email required!!')
                ->maxLength('email', 60, 'email length should not exceed 60!!');
        
        $validator
                ->notEmpty('from_email', 'email required!!')
                ->maxLength('email', 60, 'email length should not exceed 60!!');
        
        $validator
                ->notEmpty('bcc_email', 'email required!!')
                ->maxLength('email', 60, 'email length should not exceed 60!!');
        
        $validator
                ->notEmpty('support_email', 'email required!!')
                ->maxLength('email', 60, 'email length should not exceed 60!!');

        return $validator;
    }

}
