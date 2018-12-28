<?php

namespace App\Controller\Admin;

use App\Controller\AppController;

class SubMerchantsController extends AdminController {

    public function initialize() {
        parent::initialize();

        // Load Models       
        $this->loadModel('SubMerchants');

        // Set Layout
        $this->viewBuilder()->setLayout('admin');
    }

    /*
     * Developer   :  Mahesh Pradhan
     * Date        :  12th Nov 2018
     * Description :  Add new Sub-merchant.
     */

    public function add() {

        $submerchant = $this->SubMerchants->newEntity();
        if ($this->request->is('post')) {

            $data = $this->request->getData();
            $this->SubMerchants->patchEntity($submerchant, $data);

            if ($this->SubMerchants->save($submerchant)) {
                $this->Flash->success(__('Sub Merchant Added Successfully'));
                return $this->redirect(['action' => 'index']);
            }

            $this->Flash->error(__('Sub Merchant Addition Failed'));
        }

        $pageHeading = 'Add New Sub-Merchant';
        $merchantList = $this->Users->find('list')->where(['type' => 2])->select(['id', 'name']);
        $this->set(compact(['pageHeading', 'merchantList', 'submerchant']));
    }

    /*
     * Developer   :  Mahesh Pradhan
     * Date        :  12th Nov 2018
     * Description :  Sub-merchant Listing.
     */

    public function index() {
        $fields = ['name', 'email', 'payumid', 'created', 'merchant_name' => 'Merchant.name'];
        $submerchants = $this->SubMerchants->find()->select($fields)->contain(['Merchant']);

        $pageHeading = 'Sub-Merchant Listing ';
        $this->set(compact(['pageHeading', 'submerchants']));
    }

}
