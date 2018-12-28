<?php

namespace App\Controller\Admin;

use App\Controller\AppController;

class WebfrontsController extends AdminController {

    public function initialize() {
        parent::initialize();

        // Load Models
        $this->loadModel('Users');
        $this->loadModel('Webfronts');
        $this->loadModel('Payments');
        
        // Load Components
        $this->loadComponent('Paginator');

        // Set Layout
        $this->viewBuilder()->setLayout('admin');
    }

    /*
     * Developer   :  Mahesh Pradhan
     * Date        :  12th Nov 2018
     * Description :  Webfront Listing in merchant portal.
     */

    public function index() { 
        // Fetch all webfronts
        $fields = ['id', 'title', 'url', 'short_url', 'is_published', 'created', 'merchant_name' => 'Users.name'];
        $webfronts = $this->Webfronts->find()->select($fields)->contain(['Users']);


        $config = [
            'limit' => 10,
            'order' => ['Webfronts.id' => 'DESC']
        ];
        $webfronts = $this->Paginator->paginate($webfronts, $config);
        
        $pageHeading = 'View All Webfronts';
        $this->set(compact(['pageHeading', 'webfronts']));
    }
    
    public function viewPayments() {
        $data = $this->request->getQuery();

        $webfrontID = 0;
        $payments = [];

        if (!empty($data['webfront_id'])) {      
            $webfrontID = $data['webfront_id'];
            $conditions = ['webfront_id' => $data['webfront_id']];
            if(!empty($data['keyword'])) {
                $keyword = trim(urldecode($data['keyword']));
                $conditions[] = ['OR' => ['name LIKE' => "$keyword%"]];
            }
            
            $config = [
                'conditions' => $conditions,
                'limit' => 15,
                'order' => ['id' => 'DESC'],
                'contain' => [],
            ];
            $payments = $this->Paginator->paginate($this->Payments->find(), $config);
        }

        $pageHeading = 'Webfront Invoices';
        $webfrontList = $this->Webfronts->find('list', ['keyField' => 'id', 'valueField' => 'url']);
        $this->set(compact(['pageHeading', 'payments', 'webfrontList', 'webfrontID']));
    }

}
