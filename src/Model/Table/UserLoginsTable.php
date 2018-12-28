<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Datasource\ConnectionManager;

class UserLoginsTable extends Table {

    public function initialize(array $config) {
        parent::initialize($config);

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
        ]);
    }

    public function updateUserLoginLog($userID) {

        $userLogin = $this->newEntity();

        $userLogin->user_id = $userID;
        $userLogin->login_ip = getRealIpAddress();
        $userLogin->login_date = date('Y-m-d H:i:s');

        if ($this->save($userLogin)) {

            // Delete old logs & keep only last 5 login detals
            $conn = ConnectionManager::get('default');
            $sql = "DELETE FROM `user_logins` WHERE user_id = {$userID} AND id NOT IN (SELECT id FROM (SELECT id FROM `user_logins` WHERE `user_id` = {$userID} ORDER BY id DESC LIMIT 5) ul)";
            $conn->execute($sql);
        }
    }

}
