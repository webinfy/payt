<?php

namespace App\Controller\Admin;

use App\Controller\AppController;

class AdminController extends AppController {

    public function initialize() {
        parent::initialize();
        if ($this->Auth->user('id') && $this->Auth->user('type') != 1) {
            $this->Flash->error(__('Unauthorised Access!!'));
            return $this->redirect(HTTP_ROOT . "login");
        }
    }

}
