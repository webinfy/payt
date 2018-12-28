<?php

namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;

class AppController extends Controller {

    public $userId = 0;
    public $userType = 0;

    public function initialize() {
        parent::initialize();

        // Load Components
        $this->loadComponent('Flash');
        $this->loadComponent('RequestHandler', [
            'enableBeforeRedirect' => false,
        ]);
        $this->loadComponent('Auth', [
            'authenticate' => [
                'Form' => [
                    'fields' => ['username' => 'email', 'password' => 'password']
                ]
            ]
        ]);
        
        // Load Models
        $this->loadModel('Users');
      
        // Set user id , merchant id & user type to a class property for quick access
        if ($this->Auth->user('id')) {
            $this->userId = $this->Auth->user('id');
            $this->userType = $this->Auth->user('type');
            $this->merchantId = $this->Auth->user('merchant_id');
        }
    }    

    public function beforeFilter(Event $event) {     
        // Remember Me Code
        if (!$this->Auth->user('id') && isset($_COOKIE['rememberme'])) {
            $rememberme = base64_decode(urldecode($_COOKIE['rememberme']));
            $explodeCookieValue = explode('-', $rememberme);
            if (count($explodeCookieValue) >= 2) {
                $user = $this->Users->find()->where(['id' => $explodeCookieValue[0], 'uniq_id' => $explodeCookieValue[1]]);
                if ($user->count() > 0) {
                    $this->Auth->setUser($user->first()->toArray());
                }
            }
        }
    }

    public function beforeRender(Event $event) {

        if (!array_key_exists('_serialize', $this->viewVars) && in_array($this->response->getType(), ['application/json', 'application/xml'])) {
            $this->set('_serialize', true);
        }

        if ($this->userId) {
            if ($this->userType == 2) {
                $loginDetails = $this->Users->find()->where(['Users.id' => $this->Auth->user('id')])->contain(['Merchants'])->first();
            } else if ($this->userType == 3) {
                $loginDetails = $this->Users->find()->where(['Users.id' => $this->Auth->user('id')])->contain(['Employees'])->first();
            } else {
                $loginDetails = $this->Users->find()->where(['Users.id' => $this->Auth->user('id')])->first();
            }
            $this->set(compact('loginDetails'));
        }
        
        $is_logged_in = $this->request->getSession()->read('Auth.User.id') ? true : false;
        $user_type = $this->request->getSession()->read('Auth.User.type');
        $this->set(compact(['user_type','is_logged_in']));
    }

}
