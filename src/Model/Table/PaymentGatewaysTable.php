<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class PaymentGatewaysTable extends Table {

    public function initialize(array $config) {
        parent::initialize($config);

        $this->addBehavior('Timestamp');

//        $this->belongsTo('Merchants', [
//            'foreignKey' => 'merchant_id',
//            'joinType' => 'INNER'
//        ]);
    }

}
