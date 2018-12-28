<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class PaymentGatewayResponsesTable extends Table {

    public function initialize(array $config) {
        parent::initialize($config);

        $this->addBehavior('Timestamp');
    }

    public function saveToLog($data, $uniqueID) {
        $entity = $this->newEntity([
            'response' => json_encode($data),
            'payment_unique_id' => $uniqueID,
        ]);
        $this->save($entity);
    }

}
