<?php

namespace App\Controller\Admin;

use App\Controller\AppController;

class MerchantsController extends AdminController {

    public function initialize() {
        parent::initialize();

        // Load Models
        $this->loadModel('Users');
        $this->loadModel('Merchants');
        $this->loadModel('PaymentGateways');
        $this->loadModel('MerchantPaymentGateways');

        // Load Components
        $this->loadComponent('Paginator');
        $this->loadComponent('Custom');
        $this->loadComponent('SendEmail');

        // Set Layout
        $this->viewBuilder()->setLayout('admin');
    }

    public function merchantListing() {
        $conditions = ['Users.type' => 2];
        $fields = ['uniq_id', 'name', 'email', 'created', 'is_active'];
        $config = [
            'contain' => [],
            'conditions' => $conditions,
            'fields' => $fields,
            'limit' => 10,
            'order' => ['Users.id' => 'DESC']
        ];
        $merchants = $this->Paginator->paginate($this->Users->find(), $config);
        $pageHeading = "Merchant Listing";
        $this->set(compact(['pageHeading', 'merchants']));
    }

    /*
     * Developer   :  Pratap Kishore Swain
     * Date        :  21st Nov 2018
     * Description :  Edit Basic Info of merchant details. 
     */

    public function viewMerchantProfile($uniqID) {
        $merchantDetails = $this->Users->find()->where(['Users.uniq_id' => $uniqID])->contain(['Merchants'])->first();
        $merchantPaymentGateways = $this->MerchantPaymentGateways->find()->where(['merchant_id' => $merchantDetails->id])->contain(['PaymentGateways'])->order(['MerchantPaymentGateways.id' => 'DESC']);
        $pageHeading = "Merchant Profile [{$merchantDetails->name}]";
        $this->set(compact('pageHeading', 'merchantDetails', 'merchantPaymentGateways'));
        $this->render('/Merchants/view_profile');
    }

    /*
     * Developer   :  Pratap Kishore Swain
     * Date        :  21st Nov 2018
     * Description :  Edit Basic Info of merchant details. 
     */

    public function editMerchant($uniqueID) {

        $merchant = $this->Users->find()->where(['uniq_id' => $uniqueID])->contain(['Merchants'])->first();
        $merchantPaymentGateway = $this->MerchantPaymentGateways->newEntity();

        if ($this->request->is(['post', 'put', 'patch'])) {

            $data = $this->request->getData();

            switch (@$data['action']) {

                case 'BasicInfo' :

                    $this->Users->patchEntity($merchant, $data, ['associated' => ['Merchants']]);

                    if ($this->Users->save($merchant)) {
                        $this->Flash->success(__('Basic Info Updated Successfully!!'));
                        return $this->redirect($this->referer());
                    }

                    $this->Flash->error(__('Failed to update Basic Info!!'));
                    break;

                case 'PaymentGateways' :

                    $data['merchant_id'] = $merchant->id;
                    $data['unique_id'] = $this->Custom->generateUniqId();

                    $this->MerchantPaymentGateways->patchEntity($merchantPaymentGateway, $data);

                    // Set as default if not default gateway present.
                    $query = $this->MerchantPaymentGateways->find()->where(['merchant_id' => $merchant->id, 'is_default' => 1]);
                    if (!$query->count()) {
                        $merchantPaymentGateway->is_default = 1;
                    }

                    if ($this->MerchantPaymentGateways->save($merchantPaymentGateway)) {
                        $this->Flash->success(__('Payment Gateway Updated Successfully!!'));
                        return $this->redirect($this->referer());
                    }

                    $this->Flash->error(__('Failed to update Payment Gateway Info!!'));
                    break;

                case 'WebsiteAndSocial' :

                    $this->Users->patchEntity($merchant, $data, ['associated' => ['Merchants']]);

                    if ($this->Users->save($merchant)) {
                        $this->Flash->success(__('Website & Social Updated Successfully!!'));
                        return $this->redirect($this->referer());
                    }

                    $this->Flash->error(__('Failed to update Website & Social!!'));
                    break;

                case 'ProfilePicture' :

                    list(, $imgdata) = explode(';', $data['response']);
                    list(, $newimgdata) = explode(',', $imgdata);
                    $decodedimgdata = base64_decode($newimgdata);
                    $photo = md5(time() . mt_rand(1111, 9999)) . ".png";
                    $oldPhoto = $merchant->logo;

                    if (file_put_contents(MERCHANT_LOGO . $photo, $decodedimgdata)) {

                        $logoUpdated = $this->Merchants->query()->update()->set(['logo' => $photo])->where(['user_id' => $merchant->id])->execute();

                        if ($logoUpdated) {
                            file_exists(MERCHANT_LOGO . $oldPhoto) && @unlink(MERCHANT_LOGO . $oldPhoto);
                            $this->Flash->success(__('Profile Pic Updated Successfully'));
                            return $this->redirect($this->referer());
                        }
                    }

                    $this->Flash->error(__('Profile Pic Uploading Failed'));

                    break;
                case 'ChangePassword' :


                    $this->Users->patchEntity($merchant, ['password' => $data['password1'], 'password1' => $data['password1'], 'password2' => $data['password2']]);

                    if ($this->Users->save($merchant)) {
                        $this->Flash->success(__('Password Changed Successfully!!'));
                        $this->_resendEmail($merchant->uniq_id, $data);
                        return $this->redirect($this->referer());
                    }

                    $this->Flash->error(__('Current password is not correct!!'));
                    break;
                default :
                    $this->Flash->error(__('Failed to update merchant info!!'));
            }
            return $this->redirect($this->referer());
        }

        $paymentGatewayList = $this->PaymentGateways->find('list')->where(['is_active' => 1]);
        $merchantPaymentGateways = $this->MerchantPaymentGateways->find()->where(['merchant_id' => $merchant->id])->contain(['PaymentGateways'])->order(['MerchantPaymentGateways.id' => 'DESC']);

        $pageHeading = "Edit Merchant [{$merchant->name}]";

        $this->set(compact('pageHeading', 'merchant', 'merchantPaymentGateway', 'merchantPaymentGateways', 'paymentGatewayList'));

        $this->render('/Merchants/account_setup');
    }
    
     private function _resendEmail($uniqID, $data = []) {
        try {
            $this->loadModel('AdminSettings');
            $this->loadModel('MailTemplates');

            $query = $this->Users->find('all')->where(['Users.uniq_id' => $uniqID]);
            if ($query->count()) {

                $payment = $query->first();

                $adminSetting = $this->AdminSettings->find()->where(['id' => 1])->first();
                $mailTemplate = $this->MailTemplates->find()->where(['name' => 'RESET_PASSWORD', 'is_active' => 1])->first();


                $message = $this->Custom->formatEmail($mailTemplate['content'], [
                    'NAME' => $payment->name,
                    'EMAIL' => $payment->email,
                    'PASSWORD' => $data['password1'],
                ]);
                $this->SendEmail->sendEmail($payment->email, $mailTemplate->subject, $message);
            } else {
                $this->Flash->error(__('Failed to send email!!'));
            }
            return $this->redirect($this->referer());
        } catch (\Exception $ex) {
            
        }
    }

    /*
     * Developer   :  Mahesh Pradhan
     * Date        :  16th Oct 2018
     * Description :  Acivate & Deactivate Merchnat Account. 
     * @param $uniqID
     */

    public function updateStatus($uniqID) {

        $user = $this->Users->find()->where(['uniq_id' => $uniqID])->first();

        if ($user->is_active != 0) {

            $update = $this->Users->query()->update()->set(['is_active' => 0])->where(['id' => $user->id])->execute();
            if ($update) {
                $this->Flash->success(__('Merchant Inactivated Successfully'));
                return $this->redirect($this->referer());
            } else {
                $this->Flash->error(__('Merchant Inactivation Failed'));
                return $this->redirect($this->referer());
            }
        } else {

            $update = $this->Users->query()->update()->set(['is_active' => 1])->where(['id' => $user->id])->execute();
            if ($update) {
                $this->Flash->success(__('Merchant Activated Successfully'));
                return $this->redirect($this->referer());
            } else {
                $this->Flash->error(__('Merchant Activation Failed'));
                return $this->redirect($this->referer());
            }
        }
    }

    /*
     * Developer   :  Mahesh Pradhan
     * Date        :  16th Oct 2018
     * Description :  Delete a merchant & all of its associlated data. 
     * @param $uniqID
     */

    public function deleteMerchant($uniqID) {

        $user = $this->Users->find()->where(['uniq_id' => $uniqID])->first();

        if ($this->Users->deleteAll(['id' => $user->id])) {

            $this->Users->Merchants->deleteAll(['user_id' => $user->id]);

            $this->Flash->success(__('Merchant Deleted Successfully'));

            return $this->redirect($this->referer());
        } else {
            $this->Flash->error(__('Merchant Deletion Failed'));
            return $this->redirect($this->referer());
        }
    }

}
