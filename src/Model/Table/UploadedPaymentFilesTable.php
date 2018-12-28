<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Datasource\ConnectionManager;

class UploadedPaymentFilesTable extends Table {

    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('uploaded_payment_files');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->addBehavior('CounterCache', [
            'Webfronts' => ['uploaded_payment_file_count']
        ]);

        $this->belongsTo('Webfronts', [
            'foreignKey' => 'webfront_id',
            'joinType' => 'INNER'
        ]);

        $this->hasMany('Payments', [
            'dependent' => TRUE
        ]);
    }

    public function validationDefault(Validator $validator) {


        $validator
                ->date('payment_cycle_date')
                ->notEmpty('payment_cycle_date');

        $validator
                ->requirePresence('file', 'create')
                ->notEmpty('file');


        return $validator;
    }

    public function buildRules(RulesChecker $rules) {
        $rules->add($rules->existsIn(['webfront_id'], 'Webfronts'));
        return $rules;
    }
    
    public function updateCustomerCount($merchantID = NULL) {
        try {
            $conn = ConnectionManager::get('default');
            if ($merchantID > 0) {
                $sql = "UPDATE `uploaded_payment_files` "
                        . "SET `upload_count` = (SELECT COUNT(`id`) FROM `payments` WHERE `uploaded_payment_file_id` = uploaded_payment_files.id)"
                        . "WHERE webfront_id IN(SELECT id FROM `webfronts` WHERE merchant_id = :merchantID)";
                $stmt = $conn->prepare($sql);
                $stmt->bindValue('merchantID', $merchantID);
            } else {
                $sql = "UPDATE `uploaded_payment_files` "
                        . "SET `upload_count` = (SELECT COUNT(`id`) FROM `payments` WHERE `uploaded_payment_file_id` = uploaded_payment_files.id) "
                        . "WHERE 1";
                $stmt = $conn->prepare($sql);
            }
            $stmt->execute();
        } catch (\Exception $ex) {
            
        }
    }

}
