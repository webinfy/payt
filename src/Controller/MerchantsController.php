<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Cake\Collection\Collection;

class MerchantsController extends AppController {

    public function initialize() {
        parent::initialize();

        $this->loadModel('Users');
        $this->loadModel('Merchants');
        $this->loadModel('Webfronts');
        $this->loadModel('WebfrontFields');
        $this->loadModel('WebfrontPaymentAttributes');
        $this->loadModel('WebfrontFieldValues');
        $this->loadModel('UploadedPaymentFiles');
        $this->loadModel('Payments');
        $this->loadModel('MailTemplates');
        $this->loadModel('PaymentGateways');
        $this->loadModel('MerchantPaymentGateways');

        $this->loadComponent('Custom');
        $this->loadComponent('SendEmail');
        $this->loadComponent('Paginator');

        $this->viewBuilder()->setLayout('default');
        $user = $this->Auth->user();
        if($this->request->getQuery('view'))
        {
            $user['start_date'] = $this->request->getQuery('from');
            $user['end_date'] = $this->request->getQuery('to');
        }
        else
        {
            if (!isset($user['start_date'])) {
                $today = date("Y-m-d");
                $user['start_date'] = date("Y-m-d",strtotime($today.' -1 month +1 day'));
                $user['end_date'] = $today;
            }
        }
        $this->Auth->setUser($user);
        $this->from = $this->Auth->user('start_date');
        $this->to = $this->Auth->user('end_date');
    }

    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        $this->Auth->allow([]);
    }

    public function index() {
        $from = $this->from;
        $to = $this->to;
        $piechart = $this->piechart(array('paid' => 1,'unpaid' => 0),$from,$to);
        $this->set(compact('piechart','from','to'));
    }
    protected function getWebfrontsIds()
    {
        $webfront_id = $this->Webfronts->find()->where(['merchant_id'=>$this->Auth->user('merchant_id')])->select('id')->enableHydration(false)->toList();
        $webfront_ids = array();
        foreach ($webfront_id as $key => $value) {
            array_push($webfront_ids, $value['id']);
        }
        return $webfront_ids;
    }
    public function paymentSuccessRatio() {
        if (!$this->Auth->user('id')) {
            return $this->redirect(HTTP_ROOT . "login");
        }
        $from = $this->from;
        $to = $this->to;

        $query = $this->Payments->find();
        $query->select(['unmappedstatus'])->distinct()->where(function ($exp,$q) {
            return $exp->isNotNull('unmappedstatus');
        });
        $query->enableHydration(false); // Results as arrays instead of entities
        $result = $query->toList();
        $unmappedstatus = [];
        foreach ($result as $key => $value) {
            array_push($unmappedstatus, $value['unmappedstatus']);
        }

        $donutchart = $this->donutchart($unmappedstatus,$from,$to);
        $this->set(compact('donutchart','from','to'));
    }
    public function modesOfPayment() {
        if (!$this->Auth->user('id')) {
            return $this->redirect(HTTP_ROOT . "login");
        }
        $query = $this->Payments->find();
        $query->select(['mode'])->distinct()->where(function ($exp,$q) {
            return $exp->isNotNull('mode');
        });
        $query->enableHydration(false); // Results as arrays instead of entities
        $result = $query->toList();
        $paymentmod = [];
        foreach ($result as $key => $value) {
            array_push($paymentmod, $value['mode']);
        }
        $from = $this->from;
        $to = $this->to;
        $paymentmod_data = $this->paymentmod($paymentmod,$from,$to);

        $this->set(compact('paymentmod_data','from','to'));
    }
    public function dateWiseStatus() {
        if (!$this->Auth->user('id')) {
            return $this->redirect(HTTP_ROOT . "login");
        }
        $from = $this->from;
        $to = $this->to;
        $this->set(compact('barchart','from','to'));
    }
    protected function piechart($conditions,$from_date,$to_date)
    {
        $query = $this->Payments->find()->where(function($exp) use ($from_date,$to_date) {
                return $exp->between('created', $from_date, $to_date, 'date')->in('webfront_id',$this->getWebfrontsIds());
            });
        $successfullPayment = $query->newExpr()
            ->addCase($query->newExpr()->add(['status' => 1]), 1, 'integer');

        $unsuccessfullPayment = $query->newExpr()
            ->addCase($query->newExpr()->add(['status' => 0]), 1, 'integer');

        $query->select([
            'paid' => $query->func()->count($successfullPayment),
            'unpaid' => $query->func()->count($unsuccessfullPayment)
        ]);
        $query->enableHydration(false); // Results as arrays instead of entities
        $result = $query->toList()[0];
        $color = array("paid"=>"green","unpaid"=> "red");
        foreach ($result as $key => $value) {
            $data[] = array('section'=>$key,'total'=> $value,'color' =>$color[$key]);
        }
        return $data;                  
    }
    protected function donutchart($conditions,$from_date,$to_date)
    {
        $query = $this->Payments->find()->where(function($exp) use ($from_date,$to_date) {
                return $exp->between('payment_date', $from_date, $to_date, 'date')->in('webfront_id',$this->getWebfrontsIds());
            });
        foreach ($conditions as $value) {
            $paytype[$value] = $query->newExpr()->addCase($query->newExpr()->add(['unmappedstatus' => $value]), 1, 'integer');
            $request[$value] = $query->func()->count($paytype[$value]);
        }
        $query->select($request);
        $query->enableHydration(false); // Results as arrays instead of entities
        $result = $query->toList()[0];
        foreach ($result as $key => $value) {
            $data[] = array('section'=>$key,'total'=> $value);
        }
        return $data;                   
    }
    public function barchart()
    {
        $input['from'] = $this->from;
        $input['to'] = $this->to;
        $input['period'] = $this->request->getQuery('period');
        switch ($input['period']) {
            case 'Month':
                echo json_encode($this->monthlybarchart($input));
                break;
            case 'Week':
                echo json_encode($this->weeklybarchart($input));
                break;
            case 'Day':
                echo json_encode($this->dailybarchart($input));
                break;
            
            default:
                echo "ajax call tempered";
                break;
        }
        die;
    }
    protected function monthlybarchart($input)
    {
        $time1  = strtotime($input['from']);
        $time2  = strtotime($input['to']);
        $timetravel = $time1;
        $query = $this->Payments->find()
        ->where(['payment_date >=' => $input['from'], 'payment_date <=' => $input['to'], 'webfront_id IN' =>$this->getWebfrontsIds() ]);
        while($timetravel <= $time2) {
            $month = date('M-Y', $timetravel);
            $month_num = (int)date('m', $timetravel);
            $year = date('Y',$timetravel);

            $monthlypaid[$month] = $query->newExpr()->addCase($query->newExpr()->add(['Month(Payments.created)' => $month_num,'Year(Payments.created)' => $year,'status'=>1]), 1, 'integer');
            $monthly_paid_query[$month] = $query->func()->count($monthlypaid[$month]);

            $monthlyunpaid[$month] = $query->newExpr()->addCase($query->newExpr()->add(['Month(Payments.created)' => $month_num,'Year(Payments.created)' => $year,'status'=>0]), 1, 'integer');
            $monthly_unpaid_query[$month] = $query->func()->count($monthlyunpaid[$month]);
            // echo "before ".date("Y-m-d",$timetravel);
            $timetravel += strtotime('+1 month', 0);
            // echo "after ".date("Y-m-d",$timetravel);

        }
        $query->select($monthly_paid_query);
        $query->enableHydration(false); // Results as arrays instead of entities
        $paidresult = $query->toList()[0];

        $query->select($monthly_unpaid_query);
        $query->enableHydration(false); // Results as arrays instead of entities
        $unpaidresult = $query->toList()[0];
        $timetravel = $time1;
        while($timetravel <= $time2) {
            $monthYear = date('M-Y', $timetravel);
            $chartData[] = array('date'=> date('Y-m-d',$timetravel),'paid' => $paidresult[$monthYear], 'unpaid' => $unpaidresult[$monthYear]);
            $timetravel += strtotime('+1 month', 0);
        }
        return $chartData;
    }
    protected function weeklybarchart($input)
    {
        $time1  = strtotime($input['from']);
        $timetravel = $time1;
        $time2  = strtotime($input['to']);
        $query = $this->Payments->find()->where(['payment_date >=' => $input['from'], 'payment_date <=' => $input['to'], 'webfront_id IN' =>$this->getWebfrontsIds()]);
        while ($timetravel <= $time2) { 
            $week_key = "Week-".(int)date('W', $timetravel);
            $week_value = (int)date('W', $timetravel);
            $weeklypaid[$week_key] = $query->newExpr()->addCase($query->newExpr()->add(['Week(Payments.created)' => $week_value,'Year(Payments.created)' => date('Y',$timetravel),'status'=>1]), 1, 'integer');
            $weekly_paid_query[$week_key] = $query->func()->count($weeklypaid[$week_key]);

            $weeklyunpaid[$week_key] = $query->newExpr()->addCase($query->newExpr()->add(['Week(Payments.created)' => $week_value,'Year(Payments.created)' => date('Y',$timetravel),'status'=>0]), 1, 'integer');
            $weekly_unpaid_query[$week_key] = $query->func()->count($weeklyunpaid[$week_key]);
            $timetravel += strtotime('+1 week', 0);
        }
        
        $query->select($weekly_paid_query);
        $query->enableHydration(false); // Results as arrays instead of entities
        $paidresult = $query->toList()[0];

        $query->select($weekly_unpaid_query);
        $query->enableHydration(false); // Results as arrays instead of entities
        $unpaidresult = $query->toList()[0];
        $timetravel = $time1;
        while ($timetravel <= $time2) {
            $week_key = "Week-".(int)date('W', $timetravel);
            $chartData[] = array('date' => date('Y-m-d', $timetravel), 'paid' => $paidresult[$week_key], 'unpaid' => $unpaidresult[$week_key]);
            $timetravel += strtotime('+1 week', 0);
        }
        return $chartData;
    }
    protected function dailybarchart($input)
    {
        $time1  = strtotime($input['from']);
        $timetravel = $time1;
        $time2  = strtotime($input['to']);
        $query = $this->Payments->find()->where(['payment_date >=' => $input['from'], 'payment_date <=' => $input['to'], 'webfront_id IN' => $this->getWebfrontsIds()]);
        while ($timetravel <= $time2) { 
            $day_key = date('Y-m-d', $timetravel);
            $weeklypaid[$day_key] = $query->newExpr()->addCase($query->newExpr()->add(['Day(Payments.created)' => (int)date('d',$timetravel),'Month(Payments.created)' => (int)date('m',$timetravel),'Year(Payments.created)' => date('Y',$timetravel),'status'=>1]), 1, 'integer');
            $weekly_paid_query[$day_key] = $query->func()->count($weeklypaid[$day_key]);

            $weeklyunpaid[$day_key] = $query->newExpr()->addCase($query->newExpr()->add(['Day(Payments.created)' => (int)date('d',$timetravel),'Month(Payments.created)' => (int)date('m',$timetravel),'Year(Payments.created)' => date('Y',$timetravel),'status'=>0]), 1, 'integer');
            $weekly_unpaid_query[$day_key] = $query->func()->count($weeklyunpaid[$day_key]);
            $timetravel += strtotime('+1 day', 0);
        }
        $query->select($weekly_paid_query);
        $query->enableHydration(false); // Results as arrays instead of entities
        $paidresult = $query->toList()[0];
        $query->select($weekly_unpaid_query);
        $query->enableHydration(false); // Results as arrays instead of entities
        $unpaidresult = $query->toList()[0];
        $timetravel = $time1;
        while ($timetravel <= $time2) {
            $day_key = date('Y-m-d', $timetravel);
            $chartData[] = array('date' => date('Y-m-d', $timetravel), 'paid' => $paidresult[$day_key], 'unpaid' => $unpaidresult[$day_key]);
            $timetravel += strtotime('+1 day', 0);
        }
        return $chartData;
    }
    protected function paymentmod($conditions,$from_date,$to_date)
    {
        $query = $this->Payments->find()->where(function($exp) use ($from_date,$to_date) {
                return $exp->between('payment_date', $from_date, $to_date, 'date')->in('webfront_id',$this->getWebfrontsIds());
            });
        foreach ($conditions as $value) {
            $paytype[$value] = $query->newExpr()->addCase($query->newExpr()->add(['mode' => $value]), 1, 'integer');
            $request[$value] = $query->func()->count($paytype[$value]);
        }
        $query->select($request);
        $query->enableHydration(false); // Results as arrays instead of entities
        $result = $query->toList()[0];
        foreach ($result as $key => $value) {
            $data[] = array('section'=>$key,'total'=> $value);
        }
        return $data; 
    }
    public function viewProfile() {
        $pageHeading = "View Profile";

        $merchantDetails = $this->Users->find()->where(['Users.id' => $this->userId])->contain(['Merchants'])->first();
        $merchantPaymentGateways = $this->MerchantPaymentGateways->find()->where(['merchant_id' => $this->merchantId])->contain(['PaymentGateways'])->order(['MerchantPaymentGateways.id' => 'DESC']);
        $this->set(compact('pageHeading', 'merchantDetails', 'merchantPaymentGateways'));
    }

    /*
     * Developer   :  Pratap Kishore Swain (pratap.raddyx@gmail.com)
     * Created     :  21st Nov 2018
     * Description :  Update Merchant Accont Related Informations.
     */

    public function accountSetup() {
        $pageHeading = "Update Account Info";

        $merchant = $this->Users->find()->where(['Users.id' => $this->Auth->user('id')])->contain(['Merchants'])->first();
        $merchantPaymentGateway = $this->MerchantPaymentGateways->newEntity();

        if ($this->request->is(['post', 'put', 'patch'])) {

            $data = $this->request->getData();

            switch (@$data['action']) {

                case 'BasicInfo' :

                    $this->Users->patchEntity($merchant, $data, ['associated' => ['Merchants']]);

                    if ($this->Users->save($merchant)) {
                        $this->Flash->success(__('Basic Info Updated Successfully!!'));
                        return $this->redirect(HTTP_ROOT . "merchants/view-profile");
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
                        return $this->redirect(HTTP_ROOT . "merchants/view-profile");
                    }

                    $this->Flash->error(__('Failed to update Payment Gateway Info!!'));
                    break;

                case 'WebsiteAndSocial' :

                    $this->Users->patchEntity($merchant, $data, ['associated' => ['Merchants']]);

                    if ($this->Users->save($merchant)) {
                        $this->Flash->success(__('Website & Social Updated Successfully!!'));
                        return $this->redirect(HTTP_ROOT . "merchants/view-profile");
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
                            return $this->redirect(HTTP_ROOT . "merchants/view-profile");
                        }
                    }

                    $this->Flash->error(__('Profile Pic Uploading Failed'));

                    break;
                case 'ChangePassword' :

                    $userEntity = $this->Users->get($this->Auth->user('id'));
                    $userFields['fields'] = [['old_password', 'password1', 'password2'], 'validate' => 'password'];

                    $this->Users->patchEntity($userEntity, ['old_password' => $data['old_password'], 'password' => $data['password1'], 'password1' => $data['password1'], 'password2' => $data['password2']], ['validate' => 'password']);

                    if ($this->Users->save($userEntity)) {
                        $this->Flash->success(__('Password Changed Successfully!!'));
                        return $this->redirect(HTTP_ROOT . "merchants/view-profile");
                    }

                    $this->Flash->error(__('Current password is not correct!!'));
                    break;
                default :
                    $this->Flash->error(__('Failed to update merchant info!!'));
            }
            return $this->redirect($this->referer());
        }

        $this->set(compact('pageHeading', 'merchant'));

        $paymentGatewayList = $this->PaymentGateways->find('list')->where(['is_active' => 1]);
        $merchantPaymentGateways = $this->MerchantPaymentGateways->find()->where(['merchant_id' => $merchant->id])->contain(['PaymentGateways'])->order(['MerchantPaymentGateways.id' => 'DESC']);
        $this->set(compact('merchantPaymentGateway', 'merchantPaymentGateways', 'paymentGatewayList'));
    }

    /*
     * Developer   :  Pratap Kishore Swain (pratap.raddyx@gmail.com)
     * Created     :  15th Nov Feb 2018
     * Description :  Delete Payment Gateway Info.
     */

    public function deletePaymentInfo($uniqID = NULL) {
        $entity = $this->MerchantPaymentGateways->find()->where(['unique_id' => $uniqID])->first();

        if ($this->MerchantPaymentGateways->delete($entity)) {
            if ($entity->is_default) {
                $query = $this->MerchantPaymentGateways->find()->where(['merchant_id' => $entity->merchant_id])->order(['id' => 'DESC']);
                if ($query->count()) {
                    $this->MerchantPaymentGateways->query()->update()->set(['is_default' => 1])->where(['id' => $query->first()->id])->execute();
                }
            }
            $this->Flash->success(__('Payment Gateway details deleted successfully!!.'));
            return $this->redirect(HTTP_ROOT . "merchants/view-profile");
        }

        $this->Flash->error(__('Delete Payment Gateway Failed!!'));

        return $this->redirect($this->referer());
    }

    /*
     * Developer   :  Pratap Kishore Swain (pratap.raddyx@gmail.com)
     * Created     :  15th Nov Feb 2018
     * Description :  Delete Payment Gateway Info.
     */

    public function activatePaymentGateway($uniqID = NULL) {
        $this->MerchantPaymentGateways->updateAll(['is_default' => 0], ['merchant_id' => $this->merchantId]);
        $this->MerchantPaymentGateways->query()->update()->set(['is_default' => 1])->where(['unique_id' => $uniqID])->execute();

        $this->Flash->success(__('Activated Successfully!!'));
        return $this->redirect(HTTP_ROOT . "merchants/view-profile");
    }

    public function changePassword() {

        if ($this->request->is('post')) {
            $data = $this->request->getData();

            $userEntity = $this->Users->get($this->Auth->user('id'));
            $userFields['fields'] = [['old_password', 'password1', 'password2'], 'validate' => 'password'];

            $this->Users->patchEntity($userEntity, ['old_password' => $data['old_password'], 'password' => $data['password1'], 'password1' => $data['password1'], 'password2' => $data['password2']], ['validate' => 'password']);

            if ($this->Users->save($userEntity)) {
                $this->Flash->success(__('Password changed successfully!!'));
                return $this->redirect($this->referer());
            } else {
                $this->Flash->error(__('Current password is not correct!!'));
            }
        }

        $pageHeading = "Account Setup";
        $this->set(compact('pageHeading'));
    }

    public function basicWebfronts() {
        $pageHeading = "Basic Webfront Listing ";
        
        $conditions = ['Webfronts.merchant_id' => $this->merchantId, 'Webfronts.type' => 0];
        $config = [
            'conditions' => $conditions,
            'limit' => 25
        ];

        $webfronts = $this->Paginator->paginate($this->Webfronts->find(), $config);
        

        $this->set(compact('webfronts', 'pageHeading'));
    }

    public function advanceWebfronts() {
        
        $conditions = ['Webfronts.merchant_id' => $this->merchantId, 'Webfronts.type' => 1];
        $fields = ['id', 'title', 'url', 'unique_id', 'is_published', 'short_url', 'qr', 'created'];
        $config = [
            'conditions' => $conditions,
            'fields' => $fields,
            'limit' => 25
        ];

        $webfronts = $this->Paginator->paginate($this->Webfronts->find(), $config);

        $pageHeading = "Advance Webfronts";
        $this->set(compact(['pageHeading', 'webfronts']));
    }

    public function editAdvanceWebfront($uid) {

        $tab = 'websitecontent';
        $getParameters = $this->request->getQueryParams();
        if (isset($getParameters['tab'])) {
            $tab = $getParameters['tab'];
        }

        $webfront = $this->Webfronts->find()->where(['unique_id' => $uid, 'merchant_id' => $this->merchantId])->contain(['WebfrontFields.WebfrontFieldValues'])->first();
        if ($webfront->payment_cycle_date && $webfront->payment_cycle_date != '0000-00-00') {
            $webfront->payment_cycle_date = date_format($webfront->payment_cycle_date, 'Y-m-d');
        }
        $customerFields = [];
        foreach ($webfront->webfront_fields as $webfrontField) {
            $customerFields[$webfrontField->key_name] = [
                'value' => $webfrontField->name,
                'input_type' => $webfrontField->input_type,
                'validation_id' => $webfrontField->validation_id,
                'webfront_field_values' => $webfrontField->webfront_field_values
            ];
        }
        $webfront->customer_fields = $customerFields;
        unset($webfront->webfront_fields);

        $webfrontPaymentAttributes = $this->WebfrontPaymentAttributes->find()->where(['webfront_id' => $webfront->id]);

        $numfields = count($webfrontPaymentAttributes->toArray());
        $collection = new Collection($webfrontPaymentAttributes);
        $total = $collection->sumOf('value');

        if ($this->request->is(['put', 'post'])) {

            $data = $this->request->getData();
            // Update Webfront Content
            $step = $data['step'];

            if ($step == 'content') {

                $this->Webfronts->patchEntity($webfront, $data);

                $url = HTTP_ROOT . $webfront->url;
                $webfront->short_url = $this->Custom->shortUrlGenerator($url);
                $webfront->qr = $this->Custom->qrCodeGenerator($url, $webfront->url);

                if ($this->Webfronts->save($webfront)) {
                    $this->Flash->success(__('Webfront Updated Successfully!!'));
                    return $this->redirect($this->referer() . '?tab=profilepicture');
                } else {
                    $this->Flash->error(__('Webfront Updation Failed'));
                }
            } else if ($step == 'logo') {

                $fileName = $this->Custom->uploadImage($data['logo'], WEBFRONT_LOGO);
                $oldLogo = $webfront->logo;
                if ($fileName) {
                    // Update new image name to the db
                    $update = $this->Webfronts->query()->update()->set(['logo' => $fileName])->where(['id' => $webfront->id])->execute();
                    if ($update) {
                        file_exists(WEBFRONT_LOGO . $oldLogo) && @unlink(WEBFRONT_LOGO . $oldLogo);
                        $this->Flash->success(__('Webfront Logo Updated Successfully!!'));
                        return $this->redirect($this->referer() . '?tab=customerfields');
                    }
                } else {
                    $this->Flash->error(__('Webfront Logo Uploading Failed'));
                }
            } else if ($step == 'customer_fields') {


                // Updating Webfronts Table Starts
                $this->Webfronts->patchEntity($webfront, $data);

                if ($this->Webfronts->save($webfront)) {

                    // Save Webfront Fields for customer details inputs
                    $modified = date('Y-m-d H:i:s');

                    foreach ($data['customer_fields'] as $key => $customerField) {

                        $customerField['name'] = $customerField['value'];

                        if (!empty($customerField['name'])) {

                            $query = $this->WebfrontFields->find()->where(['webfront_id' => $webfront->id, 'key_name' => $key]);
                            if ($query->count()) {
                                $webfrontField = $query->first();
                            } else {
                                $webfrontField = $this->WebfrontFields->newEntity();
                                $webfrontField->webfront_id = $webfront->id;
                                $webfrontField->key_name = $key;
                            }

                            $webfrontField->modified = $modified;
                            $this->WebfrontFields->patchEntity($webfrontField, $customerField);

                            if ($this->WebfrontFields->save($webfrontField)) {

                                // Remove old options & save new options for WebfrontFields
                                $this->WebfrontFieldValues->deleteAll(['webfront_field_id' => $webfrontField->id]);

                                if (!empty($webfrontField['options'])) {
                                    foreach ($webfrontField['options'] as $option) {
                                        if (!empty($option)) {
                                            $webfrontFieldValue = $this->WebfrontFieldValues->newEntity(['webfront_field_id' => $webfrontField->id, 'value' => $option]);
                                            $this->WebfrontFieldValues->save($webfrontFieldValue);
                                        }
                                    }
                                }
                            }
                        }
                    }

                    // Delete unused fields
                    $this->WebfrontFields->deleteAll(['webfront_id' => $webfront->id, 'modified !=' => $modified]);

                    $this->Flash->success(__('Customer Fields Updated Successfully!!'));
                    return $this->redirect($this->referer() . '?tab=paymentfields');
                }

                $this->Flash->error(__('Failed to update customer fields!!'));
            } else if ($step == 'payment_fields') {
                $this->_createPaymentAttribute($data, $webfront);
            }
        }


        $pageHeading = "Edit Advance Webfront [$webfront->title]";
        $this->loadModel('Validations');
        $validationList = $this->Validations->find('list');
        $inputTypeList = ['1' => 'Textbox', '2' => 'Text Area', '3' => 'Radio Button', '4' => 'Dropdown'];
        $this->set(compact(['pageHeading', 'validationList', 'inputTypeList', 'webfront', 'webfrontPaymentAttributes', 'numfields', 'total', 'tab']));
    }

    public function publishingAdvanceWebfront($uid) {

        $publishing = ['is_published' => 0];
        $msg = 'The Webfront Unpublished Successfully';
        $webfront = $this->Webfronts->find()->where(['unique_id' => $uid])->first();
        if ($webfront->is_published == 0) {
            $publishing['is_published'] = 1;
            $msg = 'The Webfront Published Successfully';
        }


        $update = $this->Webfronts->query()->update()->set($publishing)->where(['unique_id' => $uid, 'merchant_id' => $this->merchantId])->execute();
        if ($update) {
            $this->Flash->success(__($msg));
            return $this->redirect($this->referer());
        } else {
            $this->Flash->error(__('Publishing Failed'));
            return $this->redirect($this->referer());
        }
    }

    public function deleteAdvanceWebfront($uniqID = NULL) {

        $webfront = $this->Webfronts->find()->where(['unique_id' => $uniqID])->first();

        if ($this->Webfronts->delete($webfront)) {
            $this->Flash->Success(__('Webfront has been deleted Successfully!!'));
            return $this->redirect($this->referer());
        } else {
            $this->Flash->error(__('Webfront couldnot be deleted!!'));
            return $this->redirect($this->referer());
        }
    }

    /*
     * Developer   :  Pradeepta Kumar Khatoi
     * Date        :  24th Oct 2018
     * Description :  Check if webfront url available or not.
     */

    public function checkWebfrontUrlAvail() {
        $url = trim(urldecode($_REQUEST['url']));
        if (!empty($_REQUEST['webfront_id'])) {
            $query = $this->Webfronts->find('all')->where(['url' => $url, 'id !=' => $_REQUEST['webfront_id']]);
        } else {
            $query = $this->Webfronts->find('all')->where(['url' => $url]);
        }
        if ($query->count()) {
            echo json_encode(false);
        } else {
            echo json_encode(true);
        }
        exit;
    }

    /*
     * Developer   :  Pratap Kishore Swain
     * Date        :  28th Nov 2018
     * Description :  Check if Profile url available or not.
     */

    public function checkProfileUrlAvail() {
        $data = $_REQUEST;
        $profileUrl = trim(urldecode($data['merchant']['profile_url']));

        if (isset($_REQUEST['merchant_id'])) {
            $query = $this->Merchants->find('all')->where(['AND' => ['Merchants.profile_url' => $profileUrl, 'user_id !=' => $_REQUEST['merchant_id']]]);
        }

        if ($query->count()) {
            echo json_encode(false);
        } else {
            echo json_encode(true);
        }
        exit;
    }

    private function _createPaymentAttribute($data, $webfront) {

        $data['webfront_id'] = $webfront->id;
        $webfrontPaymentAttribute = $this->WebfrontPaymentAttributes->newEntity();
        $this->WebfrontPaymentAttributes->patchEntity($webfrontPaymentAttribute, $data);
        if ($this->WebfrontPaymentAttributes->save($webfrontPaymentAttribute)) {
            $this->Flash->success(__('WebfrontPaymentAttributes Table Insertion Successfull'));
        } else {
            $this->Flash->error(__('WebfrontPaymentAttributes Table Insertion Failed'));
        }
        return $this->redirect($this->referer() . '?tab=paymentfields');
    }

    public function editPaymentAttribute($id) {

        $webfrontPaymentAttributes = $this->WebfrontPaymentAttributes->get($id);

        if ($this->request->is('put')) {

            $webfrontPaymentAttribute = $this->WebfrontPaymentAttributes->get($id);

            if ($this->request->is('put')) {

                $data = $this->request->getData();

                if ($data['is_user_entered'] == 0) {
                    $data['value'] = NULL;
                }
                $this->WebfrontPaymentAttributes->patchEntity($webfrontPaymentAttribute, $data);

                if ($this->WebfrontPaymentAttributes->save($webfrontPaymentAttributes)) {

                    if ($this->WebfrontPaymentAttributes->save($webfrontPaymentAttribute)) {

                        $this->Flash->success(__('Payment Attribute Updated Successfully'));
                        return $this->redirect($this->referer() . '?tab=paymentfields');
                    } else {
                        $this->Flash->error(__('Payment Attribute Updation Failed'));
                        return $this->redirect($this->referer() . '?tab=paymentfields');
                    }
                }
            }
        }
    }

    public function deletePaymentAttribute($id) {

        $webfrontPaymentAttribute = $this->WebfrontPaymentAttributes->get($id);

        if ($this->WebfrontPaymentAttributes->delete($webfrontPaymentAttribute)) {
            $this->Flash->success(__('Field Deleted Successfully'));
            return $this->redirect($this->referer() . '?tab=paymentfields');
        } else {
            $this->Flash->error(__('Field Deletion Failed'));
            return $this->redirect($this->referer() . '?tab=paymentfields');
        }
    }

    public function createAdvanceWebfront() {

        $webfront = $this->Webfronts->newEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();

            $data['merchant_id'] = $this->merchantId;
            $data['type'] = 1;
            $data['unique_id'] = $this->Custom->generateUniqId();

            $this->Webfronts->patchEntity($webfront, $data);

            $url = HTTP_ROOT . $webfront->url;
            $webfront->short_url = $this->Custom->shortUrlGenerator($url);
            $webfront->qr = $this->Custom->qrCodeGenerator($url, $webfront->url);

            if ($this->Webfronts->save($webfront)) {
                $this->Flash->success(__('Advance Webfront Created Successfully'));
                return $this->redirect(HTTP_ROOT . 'merchants/edit-advance-webfront/' . $webfront->unique_id . '?tab=profilepicture');
            } else {
                $err = $webfront->getErrors() ? getFirstError($webfront->getErrors()) : 'Webfront Creation Failed!!';
                $this->Flash->error(__($err));
            }
        }

        $pageHeading = "Advance Webfronts";
        $webfront->email = $this->Auth->user('email');
        $webfront->phone = $this->Auth->user('phone');
        $this->set(compact(['pageHeading', 'webfront']));
    }

    public function createBasicWebfront($uniqueID = NULL) {

        $pageHeading = "Create a Basic Webfront";

        if ($uniqueID) {
            $webfront = $this->Webfronts->find()->where(['Webfronts.unique_id' => $uniqueID, 'merchant_id' => $this->merchantId])->first();
        } else {
            $webfront = $this->Webfronts->newEntity();
        }

        if ($this->request->is('post')) {

            $data = $this->request->getData();

            $webfront = $this->Webfronts->patchEntity($webfront, $data);
            $webfront->merchant_id = $this->merchantId;
            $webfront->url = trim($data['url']);
            $webfront->unique_id = $this->Custom->generateUniqNumber();

            $url = HTTP_ROOT . $webfront->url;
            $webfront->short_url = $this->Custom->shortUrlGenerator($url);
            $webfront->qr = $this->Custom->qrCodeGenerator($url, $webfront->url);

            if ($this->Webfronts->save($webfront)) {
                $this->Flash->Success(__('Webfront created successfully!!'));
                return $this->redirect(HTTP_ROOT . "merchants/edit-basic-webfront/{$webfront->unique_id}" . '?tab=payuinfo');
            }

            $this->Flash->error(__('Some error occured. Please try again!!'));
        }

        if (!$uniqueID) {
            $webfront->email = $this->Auth->user('email');
            $webfront->phone = $this->Auth->user('phone');
        }
        $this->set(compact('webfront', 'pageHeading'));
    }

    public function editBasicWebfront($uniqueID = NULL) {

        $tab = 'basicinfo';
        $param = $this->request->getQueryParams();
        if (isset($param['tab'])) {
            $tab = $param['tab'];
        }

        // Fetch Webfront & WebfrontFields
        $webfront = $this->Webfronts->find()->where(['Webfronts.unique_id' => $uniqueID, 'merchant_id' => $this->merchantId])->contain([])->first();
        $webfront->webfront_fields = $this->WebfrontFields->find('list', ['keyField' => 'key_name', 'valueField' => 'name'])->where(['webfront_id' => $webfront->id])->toArray();
        $webfront->payment_fields = $this->WebfrontPaymentAttributes->find('list', ['keyField' => 'key_name', 'valueField' => 'name'])->where(['webfront_id' => $webfront->id])->toArray();

        if ($this->request->is(['put', 'patch'])) {

            $data = $this->request->getData();

            // Update Webfront
            $webfront = $this->Webfronts->patchEntity($webfront, $data);
            $webfront->merchant_id = $this->merchantId;

            $url = HTTP_ROOT . $webfront->url;
            $webfront->short_url = $this->Custom->shortUrlGenerator($url);
            $webfront->qr = $this->Custom->qrCodeGenerator($url, $webfront->url);

            if ($this->Webfronts->save($webfront)) {

                $webfrontID = $webfront->id;

                // Update WebfrontFields
                $modified = date("Y-m-d H:i:s");

                if (!empty($data['webfront_fields'])) {

                    foreach ($data['webfront_fields'] as $key => $value) {
                        $value = trim($value);
                        if (!empty($value)) {
                            $query = $this->WebfrontFields->find()->where(['webfront_id' => $webfrontID, 'key_name' => $key]);
                            if ($query->count()) {
                                $webfrontField = $query->first();
                            } else {
                                $webfrontField = $this->WebfrontFields->newEntity();
                            }
                            $webfrontField->webfront_id = $webfrontID;
                            $webfrontField->key_name = $key;
                            $webfrontField->name = $value;
                            $webfrontField->is_mandatory = 1;

                            $webfrontField->modified = $modified;
                            $this->WebfrontFields->save($webfrontField);
                        }
                    }

                    // Delete Unused WebfrontFields
                    $this->WebfrontFields->deleteAll(['webfront_id' => $webfrontID, 'modified !=' => $modified]);
                    $this->Flash->Success(__('Customer Fields Updated Successfully!!'));
                    return $this->redirect(HTTP_ROOT . "merchants/edit-basic-webfront/{$webfront->unique_id}" . '?tab=websitesocial');
                } else if (!empty($data['payment_fields'])) {

                    foreach ($data['payment_fields'] as $key1 => $value1) {
                        $value1 = trim($value1);
                        if (!empty($value1)) {
                            $query = $this->WebfrontPaymentAttributes->find()->where(['webfront_id' => $webfrontID, 'key_name' => $key1]);
                            if ($query->count()) {
                                $webfrontAttributeField = $query->first();
                            } else {
                                $webfrontAttributeField = $this->WebfrontPaymentAttributes->newEntity();
                            }
                            $webfrontAttributeField->webfront_id = $webfrontID;
                            $webfrontAttributeField->key_name = $key1;
                            $webfrontAttributeField->name = $value1;
                            $webfrontAttributeField->is_user_entered = 1;

                            $webfrontAttributeField->modified = $modified;
                            $this->WebfrontPaymentAttributes->save($webfrontAttributeField);
                        }
                    }

                    // Delete Unused WebfrontFields
                    $this->WebfrontPaymentAttributes->deleteAll(['webfront_id' => $webfrontID, 'modified !=' => $modified]);
                    $this->Flash->Success(__('Payment Fields Updated Successfully!!'));
                    return $this->redirect(HTTP_ROOT . "merchants/edit-basic-webfront/{$webfront->unique_id}" . '?tab=webfrontlogo');
                } else if (!empty($data['newlogo'])) {

                    if (empty($data['newlogo']['name'])) {
                        $this->Flash->error(__('Please browse an image!'));
                    } else {
                        
                        $fileName = $this->Custom->uploadImage($data['newlogo'], WEBFRONT_LOGO);
                        if ($fileName) {
                            // Update new image name to the db
                            $logoUpdated = $this->Webfronts->query()->update()->set(['logo' => $fileName])->where(['id' => $webfrontID])->execute();
                            if ($logoUpdated) {                                
                                file_exists(WEBFRONT_LOGO . $webfront->logo) &&  @unlink(WEBFRONT_LOGO . $webfront->logo);  
                                $this->Flash->success(__('Webfront Logo Updated Successfully'));
                                return $this->redirect(HTTP_ROOT . "merchants/edit-basic-webfront/{$webfront->unique_id}" . '?tab=profilepicture');
                            }                            
                        } 
                        
                        $this->Flash->error(__('Webfront Logo Uploading Failed'));                        
                    }
                } else {

                    $this->Flash->Success(__('Website Content Updated Successfully!!'));
                    return $this->redirect(HTTP_ROOT . "merchants/edit-basic-webfront/{$webfront->unique_id}" . '?tab=payuinfo');
                }
            } else {
                $this->Flash->error(__('Some error occured. Please try again!!'));
            }
        }

        $pageHeading = "Edit Basic Webfront [$webfront->title]";
        $this->set(compact('webfront', 'pageHeading', 'tab'));
    }

    public function downloadSampleExcel($id = NULL) {
        try {

            $query = $this->Webfronts->find()->where(['id' => $id]);

            if ($query->count() > 0) {

                $webfront = $query->first();
                $customerFields = $this->WebfrontFields->find('all')->where(['webfront_id' => $webfront->id]);
                $paymentFields = $this->WebfrontPaymentAttributes->find('all')->where(['webfront_id' => $webfront->id]);

                $totalColumn = $customerFields->count() + $paymentFields->count() + 6;

                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();

                // SetHeading
                $count = 0;
                $head = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
                foreach (range('A', $head[$totalColumn - 1]) as $columnID) {
                    $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
                }

                $sheet->setCellValue("{$head[$count++]}1", $webfront->customer_name_alias);
                $sheet->setCellValue("{$head[$count++]}1", $webfront->customer_reference_number_alias);
                $sheet->setCellValue("{$head[$count++]}1", $webfront->customer_email_alias);
                $sheet->setCellValue("{$head[$count++]}1", $webfront->customer_phone_alias);
                foreach ($customerFields as $customerField) {
                    $sheet->setCellValue("{$head[$count++]}1", $customerField->name);
                }
                foreach ($paymentFields as $paymentField) {
                    $sheet->setCellValue("{$head[$count++]}1", $paymentField->name);
                }
                $sheet->setCellValue("{$head[$count++]}1", $webfront->total_amount_alias);
                $sheet->setCellValue("{$head[$count]}1", $webfront->customer_note_alias);


                $sheet->getStyle("A1:{$head[$count]}1")->getAlignment()->applyFromArray([
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_FILL,
                ]);

                $sheet->getStyle("A1:{$head[$count]}1")->applyFromArray([
                    'font' => [ 'name' => 'Cambria', 'bold' => true, 'italic' => false, 'strikethrough' => false, 'color' => [ 'rgb' => '7, 7, 7']],
                    'borders' => ['color' => [ 'rgb' => '808080']], 'top' => [ 'borderStyle' => Border::BORDER_DASHDOT, 'color' => [ 'rgb' => '808080']],
                    'quotePrefix' => true]);

                $writer = new Xlsx($spreadsheet);
                $fileName = md5(microtime()) . rand() . ".xlsx";
                $filePath = WWW_ROOT . 'sample' . DS . $fileName;
                $writer->save($filePath);

                $response = $this->response->withFile($filePath, ['download' => true, 'name' => "SampleExcel" . ucfirst($webfront->url) . ".xlsx"]);

                return $response;
            } else {
                $this->Flash->error(__('Error Occured!!'));
                return $this->redirect($this->referer());
            }
        } catch (\Exception $ex) {
            
        } finally {
            
        }
    }

    /*
     * Developer   :  Pratap Kishore Swain (pratap.raddyx@gmail.com)
     * Created     :  9th Nov Feb 2018
     * Description :  Upload Excel/Csv file for an basic webfront.
     */

    public function uploadExcel() {

        $responseData['status'] = 'failure';
        $responseData['message'] = 'Some error occured while uploading the excel/csv!!';

        if (!$this->request->is('post')) {
            $responseData['message'] = 'Requested Method Not Supported!!';
            sendResponse($responseData);
        }

        $data = $this->request->getData();
        $ext = pathinfo($data["file"]["name"], PATHINFO_EXTENSION);

        // If payment_cycle_date < current date
        if (date('Y-m-d') > date('Y-m-d', strtotime($data['payment_cycle_date']))) {
            $responseData['message'] = 'Payment Cycle Date should not less that current date!!';
            sendResponse($responseData);
        }

        // If no file is browsed
        if (empty($data["file"]["name"])) {
            $responseData['message'] = 'Please select file for import!!';
            sendResponse($responseData);
        }

        // Browsed file must be a  xls, xlsx or csv
        if (!in_array($ext, ['xls', 'xlsx', 'csv'])) {
            $responseData['message'] = 'Please select excel/csv file only!!';
            sendResponse($responseData);
        }

        // Upload file to server
        $fileName = time() . rand(1111, 9999) . "." . $ext;
        $targetFile = UPLOADED_PAYMENT_FILES . $fileName;
        move_uploaded_file($data["file"]["tmp_name"], $targetFile);

        $webfront = $this->Webfronts->find()->where(['Webfronts.unique_id' => $data['unique_id']])->contain(['Merchants'])->first();
        $customerFields = $this->WebfrontFields->find('all')->where(['webfront_id' => $webfront->id]);
        $paymentFields = $this->WebfrontPaymentAttributes->find('all')->where(['webfront_id' => $webfront->id]);

        $customerFieldCount = $customerFields->count();
        $paymentFieldCount = $paymentFields->count();
        $totalColumn = $customerFieldCount + $paymentFieldCount + 6;

        $customerFields = $customerFields->toArray();
        $paymentFields = $paymentFields->toArray();

        $inputFileName = UPLOADED_PAYMENT_FILES . $fileName;

        try {
            $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($inputFileName);
            $inputFileType->setReadDataOnly(true);
            $objPHPExcel = $inputFileType->load($inputFileName);
        } catch (\Exception $e) {
            $responseData['message'] = 'Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage();
            sendResponse($responseData);
        }

        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        // Header
        $getHeading = $sheet->rangeToArray('A' . 1 . ':' . $highestColumn . 1, NULL, TRUE, FALSE);
        $heading = array_filter(array_map('trim', $getHeading[0]));

        // Check for validation error
        $validateData = $this->_validateExcel($webfront, $fileName);
        if ($validateData['status'] == 'error') {
            $responseData['message'] = $validateData['message'];
            $responseData['errors'] = !empty($validateData['errors']) ? $validateData['errors'] : [];
            sendResponse($responseData);
        }

        $uploadedPaymentFile = $this->UploadedPaymentFiles->newEntity();
        $uploadedPaymentFile->webfront_id = $webfront->id;
        $uploadedPaymentFile->title = $data['title'];
        $uploadedPaymentFile->payment_cycle_date = $data['payment_cycle_date'];
        $uploadedPaymentFile->file = $fileName;

        if ($this->UploadedPaymentFiles->save($uploadedPaymentFile)) {

            for ($row = 2; $row <= $highestRow; $row++) {

                $paymentData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE)[0];

                $name = $paymentData[0];
                $reference_number = $paymentData[1];
                $email = !empty($paymentData[2]) ? filter_var($paymentData[2], FILTER_SANITIZE_EMAIL) : '';
                $phone = !empty($paymentData[3]) ? trim($paymentData[3]) : '';
                $fee = $paymentData[$totalColumn - 2];
                $note = $paymentData[$totalColumn - 1];

                $payment = $this->Payments->newEntity();
                $payment->uniq_id = $this->Custom->generateUniqId();
                $payment->webfront_id = $uploadedPaymentFile->webfront_id;
                $payment->uploaded_payment_file_id = $uploadedPaymentFile->id;
                $payment->name = $name;
                $payment->reference_number = $reference_number;
                $payment->email = $email;
                $payment->phone = $phone;
                $payment->note = $note;

                if ($customerFieldCount) {
                    $customerFieldValues = [];
                    $i = 1;
                    foreach ($customerFields as $customerField) {
                        $customerFieldValues[$customerField->id]['field'] = $customerField->name;
                        $customerFieldValues[$customerField->id]['value'] = $paymentData[3 + $i];
                        $i++;
                    }
                    $payment->payee_custom_fields = json_encode($customerFieldValues);
                }

                if ($paymentFieldCount) {
                    $paymentFieldValues = [];
                    $i = 1;
                    foreach ($paymentFields as $paymentField) {
                        $paymentFieldValues[$paymentField->id]['field'] = $paymentField->name;
                        $paymentFieldValues[$paymentField->id]['value'] = $paymentData[3 + $customerFieldCount + $i];
                        $i++;
                    }
                    $payment->payment_custom_fields = json_encode($paymentFieldValues);
                }

                $payment->late_fee_amount = 0; //$webfront->late_fee_amount;
                $payment->convenience_fee_amount = !empty($webfront->merchant->convenience_fee_amount) ? $webfront->merchant->convenience_fee_amount : 0;
                $payment->fee = $fee;
                $payment->created = date('Y-m-d H:i:s');
                $payment->modified = date('Y-m-d H:i:s');
                
                if ($this->Payments->save($payment)) {
                    
                    // Send SMS to customer
                    if (!empty($payment->phone) && strlen($payment->phone) == 10) {
                        $invoiceLink = HTTP_ROOT . "preview-invoice/" . $payment->uniq_id;
                        $sms = "Use this Below Link to process Payment of amount INR {$payment->fee}. {$invoiceLink}";
                        sendSMS($payment->phone, $sms);
                    }
                }
            }

            // Send Emails
            $this->_sendEmailInBackground($uploadedPaymentFile->id);

            $responseData['status'] = 'success';
            $responseData['message'] = 'File Imported Successfully!!';
        }

        sendResponse($responseData);
    }

    /*
     * Developer   :  Pratap Kishore Swain (pratap.raddyx@gmail.com)
     * Created     :  9th Nov Feb 2018
     * Description :  Append new records for an basic webfront previously uploaded file.
     */

    public function appendRecords() {

        if ($this->request->is('post')) {

            $data = $this->request->getData();

            $uploadedPaymentFile = $this->UploadedPaymentFiles->get($data['id']);

            $ext = pathinfo($data["file"]["name"], PATHINFO_EXTENSION);

            if (empty($data["file"]["name"])) {
                $this->Flash->error(__('Please select file for import!!'));
            } else if (!in_array($ext, ['xls', 'xlsx', 'csv'])) {
                $this->Flash->error(__('Please select excel/csv file only!!'));
            } else {

                $fileName = time() . rand(1111, 9999) . "." . $ext;
                $targetFile = UPLOADED_PAYMENT_FILES . $fileName;
                move_uploaded_file($data["file"]["tmp_name"], $targetFile);

                $webfront = $this->Webfronts->find()->where(['Webfronts.id' => $uploadedPaymentFile->webfront_id])->contain(['Merchants'])->first();
                $customerFields = $this->WebfrontFields->find('all')->where(['webfront_id' => $webfront->id]);
                $paymentFields = $this->WebfrontPaymentAttributes->find('all')->where(['webfront_id' => $webfront->id]);

                $customerFieldCount = $customerFields->count();
                $paymentFieldCount = $paymentFields->count();
                $totalColumn = $customerFieldCount + $paymentFieldCount + 6;

                $customerFields = $customerFields->toArray();
                $paymentFields = $paymentFields->toArray();

                $inputFileName = UPLOADED_PAYMENT_FILES . $fileName;

                try {
                    $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($inputFileName);
                    $inputFileType->setReadDataOnly(true);
                    $objPHPExcel = $inputFileType->load($inputFileName);
                } catch (Exception $e) {
                    $this->Flash->error(__('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage()));
                    return $this->redirect($this->referer());
                }

                $sheet = $objPHPExcel->getSheet(0);
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();

                //Header
                $getHeading = $sheet->rangeToArray('A' . 1 . ':' . $highestColumn . 1, NULL, TRUE, FALSE);
                $heading = array_filter(array_map('trim', $getHeading[0]));

                $validateData = $this->_validateExcel($webfront, $fileName);

                if ($validateData['status'] == 'error') {
                    $this->Flash->error(__($validateData['errors']));
                    return $this->redirect($this->referer());
                }

                for ($row = 2; $row <= $highestRow; $row++) {

                    $paymentData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE)[0];

                    $name = $paymentData[0];
                    $reference_number = $paymentData[1];
                    $email = !empty($paymentData[2]) ? filter_var($paymentData[2], FILTER_SANITIZE_EMAIL) : '';
                    $phone = !empty(trim($paymentData[3])) ? trim($paymentData[3]) : '';
                    $fee = $paymentData[$totalColumn - 2];
                    $note = $paymentData[$totalColumn - 1];

                    $payment = $this->Payments->newEntity();

                    $query = $this->Payments->find()->where(['uploaded_payment_file_id' => $uploadedPaymentFile->id, 'reference_number' => $reference_number]);
                    if ($query->count()) {
                        $payment = $query->first();
                        if ($payment->status == 1) {
                            continue; // Skip Paid Entries
                        }
                    }

                    $payment->uniq_id = $this->Custom->generateUniqId();
                    $payment->webfront_id = $uploadedPaymentFile->webfront_id;
                    $payment->uploaded_payment_file_id = $uploadedPaymentFile->id;
                    $payment->name = $name;
                    $payment->reference_number = $reference_number;
                    $payment->email = $email;
                    $payment->phone = $phone;
                    $payment->note = $note;

                    if ($customerFieldCount) {
                        $customerFieldValues = [];
                        $i = 1;
                        foreach ($customerFields as $customerField) {
                            $customerFieldValues[$customerField->id]['field'] = $customerField->name;
                            $customerFieldValues[$customerField->id]['value'] = $paymentData[3 + $i];
                            $i++;
                        }
                        $payment->payee_custom_fields = json_encode($customerFieldValues);
                    }

                    if ($paymentFieldCount) {
                        $paymentFieldValues = [];
                        $i = 1;
                        foreach ($paymentFields as $paymentField) {
                            $paymentFieldValues[$paymentField->id]['field'] = $paymentField->name;
                            $paymentFieldValues[$paymentField->id]['value'] = $paymentData[3 + $customerFieldCount + $i];
                            $i++;
                        }
                        $payment->payment_custom_fields = json_encode($paymentFieldValues);
                    }

                    $payment->late_fee_amount = $webfront->late_fee_amount;
                    $payment->convenience_fee_amount = !empty($webfront->merchant_profile->convenience_fee_amount) ? $webfront->merchant_profile->convenience_fee_amount : 0;
                    $payment->fee = $fee;
                    $payment->created = date('Y-m-d H:i:s');
                    $payment->modified = date('Y-m-d H:i:s');
                    
                    if ($this->Payments->save($payment)) {
                        // Send SMS to customer
                        if (!empty($payment->phone) && strlen($payment->phone) == 10) {
                            $invoiceLink = HTTP_ROOT . "preview-invoice/" . $payment->uniq_id;
                            $sms = "Use this Below Link to process Payment of amount INR {$payment->fee}. {$invoiceLink}";
                            sendSMS($payment->phone, $sms);
                        }
                    }
                }

                // Send Emails
                $this->_sendEmailInBackground($uploadedPaymentFile->id);

                $this->Flash->Success(__('New Records Added Successfully!!'));
            }
        }

        return $this->redirect($this->referer());
    }

    public function _validateExcel($webfront = NULL, $fileName = NULL, $errorFileName = NULL) {

        $inputFileName = UPLOADED_PAYMENT_FILES . $fileName;

        try {
            $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($inputFileName);
            $inputFileType->setReadDataOnly(true);
            $objPHPExcel = $inputFileType->load($inputFileName);
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => 'Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage()];
        }

        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        $customFieldCount = $this->WebfrontFields->find('all')->where(['webfront_id' => $webfront->id])->count();
        $paymentFieldCount = $this->WebfrontPaymentAttributes->find('all')->where(['webfront_id' => $webfront->id])->count();
        $totalColumn = $customFieldCount + $paymentFieldCount + 6;


        // Header
        $getHeading = $sheet->rangeToArray('A' . 1 . ':' . $highestColumn . 1, NULL, TRUE, FALSE);
        $heading = array_filter(array_map('trim', $getHeading[0]));

        if ($totalColumn != count($heading)) {
            return ['status' => 'error', 'message' => "Invalid Excel File. Colums does't matches!!"];
        }

        if ($this->_hasDuplicatedValues($objPHPExcel, 'B')) {
            return ['status' => 'error', 'message' => "File contains duplicate entry for Reference No!!"];
        }

        $errors = [];
        for ($row = 2; $row <= $highestRow; $row++) {

            $paymentData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE)[0];

            $name = $paymentData[0];
            $reference_number = $paymentData[1];
            $email = !empty($paymentData[2]) ? filter_var($paymentData[2], FILTER_SANITIZE_EMAIL) : '';
            $phone = !empty(trim($paymentData[3])) ? trim($paymentData[3]) : '';
            $fee = $paymentData[$totalColumn - 2];
            $note = $paymentData[$totalColumn - 1];

            // Validation Start           
            if (empty($name)) {
                $errors[$row][] = "Name is empty at row {$row}  \n";
            }
            if (empty($reference_number)) {
                $errors[$row][] = "Reference number is empty at row {$row}  \n";
            }
            if (empty($email)) {
                $errors[$row][] = "Email is empty at row {$row}  \n";
            }
            if (filter_var($email, FILTER_VALIDATE_EMAIL) == false) {
                $errors[$row][] = "Invalid email at row {$row}  \n";
            }
            if (empty($phone)) {
                $errors[$row][] = "Phone is empty at row {$row}  \n";
            }
            if (strlen($phone) != 10) {
                $errors[$row][] = "Phone No. must be of 10 digit at row {$row}  \n";
            }
            if (!ctype_digit($phone)) {
                $errors[$row][] = "Invalid Phone No.  at row {$row}  \n";
            }
            if ($fee <= 0) {
                $errors[$row][] = "Bill amount must be greater than 0 at row {$row}  \n";
            }
        }

        if (!empty($errors)) {
            return ['status' => 'error', 'message' => 'Validation Faild', 'errors' => $errors];
        }

        return ['status' => 'success'];
    }

    public function _hasDuplicatedValues($objPHPExcel, $column = 'B', $ignoreEmptyCells = false) {
        $worksheet = $objPHPExcel->getActiveSheet();
        $cells = array();
        foreach ($worksheet->getRowIterator() as $row) {
            $cell = $worksheet->getCell($column . $row->getRowIndex())->getValue();
            if (($ignoreEmptyCells == false) | (empty($cell) == false)) {
                $cells[] = $cell;
            }
        }
        if (count(array_unique($cells)) < count($cells)) {
            unset($cells);
            unset($cell);
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function viewUploads($webfrontId = NULL) {

        $webfront = $this->Webfronts->find()->where(['Webfronts.unique_id' => $webfrontId])->contain('UploadedPaymentFiles')->first();
        $paymentFiles = $this->UploadedPaymentFiles->find('all')->where(['UploadedPaymentFiles.webfront_id' => $webfront->id])->contain('Webfronts')->order(['UploadedPaymentFiles.id' => 'DESC']);
        $pageHeading = "View Uploads [$webfront->title]";
        $this->set(compact('pageHeading', 'paymentFiles', 'webfront'));
    }

    public function uploadReuse() {

        $data = $this->request->getData();

        $paymentCycleDate = $data['payment_cycle_date'];
        if ($paymentCycleDate == '') {
            $this->Flash->error(__('Please enter payment cycle date!!'));
            return $this->redirect($this->referer());
        }
        if ($paymentCycleDate <= date('Y-m-d')) {
            $this->Flash->error(__('Payment cycle date must be greater than current date!!'));
            return $this->redirect($this->referer());
        }

        $getUploadedPaymentFile = $this->UploadedPaymentFiles->get($data['id']);
        $uploadedPaymentFile = $this->UploadedPaymentFiles->newEntity();
        $uploadedPaymentFile->webfront_id = $getUploadedPaymentFile->webfront_id;
        $uploadedPaymentFile->title = $data['title'];
        $uploadedPaymentFile->payment_cycle_date = $data['payment_cycle_date'];
        $uploadedPaymentFile->file = $getUploadedPaymentFile->file;
        
        if ($this->UploadedPaymentFiles->save($uploadedPaymentFile)) {
            
            $getPayments = $this->Payments->find('all')->where(['uploaded_payment_file_id' => $data['id']]);
            
            foreach ($getPayments as $getPayment) {
                
                $payment = $this->Payments->newEntity();
                
                $payment->uniq_id = $this->Custom->generateUniqId();
                $payment->webfront_id = $uploadedPaymentFile->webfront_id;
                $payment->uploaded_payment_file_id = $uploadedPaymentFile->id;
                
                $payment->name = $getPayment->name;
                $payment->reference_number = $getPayment->reference_number;
                $payment->email = $getPayment->email;
                $payment->phone = $getPayment->phone;
                $payment->payee_custom_fields = $getPayment->payee_custom_fields;                
                $payment->fee = $getPayment->fee;
                $payment->payment_custom_fields = $getPayment->payment_custom_fields;
                $payment->convenience_fee_amount = 0;
                $payment->late_fee_amount = 0;
                
                if ($this->Payments->save($payment)) {
                    // Send SMS to customer
                    if (!empty($payment->phone) && strlen($payment->phone) == 10) {
                        $invoiceLink = HTTP_ROOT . "preview-invoice/" . $payment->uniq_id;
                        $sms = "Use this Below Link to process Payment of amount INR {$payment->fee}. {$invoiceLink}";
                        sendSMS($payment->phone, $sms);
                    }
                }
            }
            
            // Send Emails
            $this->_sendEmailInBackground($uploadedPaymentFile->id);
            
            $this->Flash->success(__('Copied Successfully!!'));
            
            return $this->redirect($this->referer());
        } else {
            $this->Flash->error(__('Some error occured.Please try again!!'));
            return $this->redirect($this->referer());
        }
    }

    public function viewReports($id = NULL) {

        $uploadFileId = $this->UploadedPaymentFiles->find()->where(['id' => $id])->first();
        $webfronts = $this->Webfronts->find()->where(['id' => $uploadFileId->webfront_id])->first();
//        pj($webfronts);exit;
        $query = $this->request->getQuery();
        $sort = ['Payments.reference_number' => 'ASC'];
        $condition = ['Payments.uploaded_payment_file_id' => $uploadFileId->id];

        if (isset($query['filter_by']) && $query['filter_by'] == 'PAID') {
            $condition[] = ['Payments.status' => 1];
        } else if (isset($query['filter_by']) && $query['filter_by'] == 'UNPAID') {
            $condition[] = ['Payments.status' => 0];
        }

        $payments = $this->Payments->find()->where($condition)->contain('UploadedPaymentFiles')->order($sort);
        $pageHeading = "View Payments[$webfronts->title]";

        $this->set(compact('payments', 'webfronts', 'pageHeading', 'uploadFileId'));
    }

    public function deleteUploadfile($Id = NULL) {
        $file = $this->UploadedPaymentFiles->find()->where(['id' => $Id])->first();

        if ($this->UploadedPaymentFiles->delete($file)) {
            $this->Flash->success(__('Records deleted successfully!!.'));
            return $this->redirect($this->referer());
        } else {
            $this->Flash->error(__('Some error occured.Please try again!!'));
            return $this->redirect($this->referer());
        }
    }

    /*
     * Developer   :  Paratap Kishore Swain
     * Date        :  22nd Nov 2018
     * Description :  Delete an invoice.
     * @param $uniqueId
     */

    public function deletePayment($uniqueId = NULL) {
        $query = $this->Payments->find()->where(['uniq_id' => $uniqueId]);

        if ($query->count() == 0) {
            $this->Flash->error(__('Record Not Found!!'));
            return $this->redirect($this->referer());
        }

        $entity = $query->first();

        if ($entity->status == 1) {
            $this->Flash->error(__("Paid Invoices can't be deleted!!"));
            return $this->redirect($this->referer());
        }

        if ($this->Payments->delete($entity)) {
            $this->Flash->success(__('Deleted Successfully!!.'));
        } else {
            $this->Flash->error(__("Deletion Failed!!"));
        }
        return $this->redirect($this->referer());
    }

    public function ajaxDeleteSelectedPayments() {

        $emp_ids = $this->request->getData('emp_ids');
        $response = ['status' => 'success', 'message' => 'Some error occured while delete payment details'];
        if ($this->Payments->deleteAll(['Payments.id IN' => $emp_ids])) {
            $response = ['status' => 'success', 'message' => 'Payment detail deleted successfully.'];
        }
        echo json_encode($response);
        exit;
    }

    public function downloadReport($fileId = NULL, $option = 0) {
        try {

            $payments = $this->Payments->find('all')->where(['Payments.uploaded_payment_file_id' => $fileId])->limit(1000);

            $objPHPExcel = new Spreadsheet();
            $sheet = $objPHPExcel->getActiveSheet();

            $count = 0;
            $head = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];

            $sheet->getStyle("A1:{$head[$count]}1")->getAlignment()->applyFromArray([
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_FILL,
            ]);

            $sheet->getStyle("A1:{$head[$count]}1")->applyFromArray([
                'font' => [ 'name' => 'Cambria', 'bold' => true, 'italic' => false, 'strikethrough' => false, 'color' => [ 'rgb' => '7, 7, 7']],
                'borders' => ['color' => ['rgb' => '808080']], 'top' => [ 'borderStyle' => Border:: BORDER_DASHDOT, 'color' => [ 'rgb' => '808080']],
                'quotePrefix' => true]);

            foreach (range('A', 'L') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            }
///SetHeading//  
            $objPHPExcel->getActiveSheet()->SetCellValue('A1', "Sr.No.");
            $objPHPExcel->getActiveSheet()->SetCellValue('B1', "Unique ID");
            $objPHPExcel->getActiveSheet()->SetCellValue('C1', "Name");
            $objPHPExcel->getActiveSheet()->SetCellValue('D1', "Invoice Number");
            $objPHPExcel->getActiveSheet()->SetCellValue('E1', "Reference Number");
            $objPHPExcel->getActiveSheet()->SetCellValue('F1', "Email Id");
            $objPHPExcel->getActiveSheet()->SetCellValue('G1', "Mobile No");
            $objPHPExcel->getActiveSheet()->SetCellValue('H1', "Total Amt");
            $objPHPExcel->getActiveSheet()->SetCellValue('I1', "Payment Date");
            $objPHPExcel->getActiveSheet()->SetCellValue('J1', "Payment Id");
            $objPHPExcel->getActiveSheet()->SetCellValue('K1', "Paid Amount");
            $objPHPExcel->getActiveSheet()->SetCellValue('L1', "Transaction Id");
            $objPHPExcel->getActiveSheet()->SetCellValue('M1', "Mode");
//Set Content
            $rowCount = 2;
            foreach ($payments as $payment) {
                $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, ($rowCount - 1));
                $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $payment->uniq_id);
                $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $payment->name);
                $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $payment->id);
                $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $payment->reference_number);
                $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $payment->email);
                $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $payment->phone);
                $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $payment->fee);
                $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $payment->payment_date);
                //Below filed will be used for Paid Payments
                $objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, $payment->id);
                //$amount = ($payment->status == 1) ? $payment->paid_amount : $payment->fee;
                $objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, $payment->paid_amount);
                $objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, $payment->txn_id);
                $objPHPExcel->getActiveSheet()->SetCellValue('M' . $rowCount, $payment->mode);

                $rowCount++;
            }

            $writer = new Xlsx($objPHPExcel);
            $fileName = "Transaction-Report-" . time() . ".xlsx";
            $filePath = WWW_ROOT . 'files/reports/' . DS . $fileName;
            $writer->save($filePath);

            $response = $this->response->withFile($filePath, [ 'download' => true, 'name' => "Transaction-Report-" . time() . ".xlsx"]);
            return $response;
        } catch (\Exception $ex) {
            
        } finally {
            
        }
    }

    public function publish($id = NULL) {
        $status = $this->Webfronts->query()->update()->set(['is_published' => 1])->where(['id' => $id])->execute();
        if ($status) {
            $this->Flash->Success(__('Webfront Published Successfully!!'));
            $this->redirect($this->referer());
        }
    }

    public function unpublish($id = NULL) {
        $status = $this->Webfronts->query()->update()->set(['is_published' => 0])->where(['id' => $id])->execute();
        if ($status) {
            $this->Flash->Success(__('Webfront UnPublished Successfully!!'));
            $this->redirect($this->referer());
        }
    }

    public function deleteWebfront($uniqueId = null) {
        $webfront = $this->Webfronts->find()->where(['unique_id' => $uniqueId])->first();

        if ($this->Webfronts->delete($webfront)) {
            $this->Flash->Success(__('Webfront has been deleted Successfully!!'));
            return $this->redirect($this->referer());
        } else {
            $this->Flash->error(__('Webfront couldnot be deleted!!'));
            return $this->redirect($this->referer());
        }
    }

    private function _sendEmailInBackground($id = NULL) {
        try {
            $this->loadModel('AdminSettings');
            $this->loadModel('MailTemplates');

            $payments = $this->Payments->find('all')->where(['uploaded_payment_file_id' => $id, 'followup_counter' => 0]);

            if ($payments->count()) {

                $adminSetting = $this->AdminSettings->find()->where(['id' => 1])->first();
                $uploadedPaymentFile = $this->UploadedPaymentFiles->get($id);
                $webfront = $this->Webfronts->find()->where(['Webfronts.id' => $uploadedPaymentFile->webfront_id])->contain(['Users'])->first();
                $mailTemplate = $this->MailTemplates->getTemplate('PAYMENT_NOTIFICATION', $webfront->id);

                foreach ($payments as $payment) {

                    $billAmount = $payment->fee;
                    $paymentLink = HTTP_ROOT . "preview-invoice/" . $payment->uniq_id;
                    $paymentLinkBtn = "<a style='background: none repeat scroll 0 0 #C20E09; border-radius: 4px;color: #FFFFFF;display: block;font-size: 14px; font-weight: bold;margin: 15px 1px;padding: 8px 10px;text-align: center;width: 130px;text-decoration:none;' href='{$paymentLink}'>Preview Invoice</a>";
                    $viewTransLink = HTTP_ROOT . $webfront->url . "?ref_no=" . $payment->reference_number;

                    $message = $this->Custom->formatEmail($mailTemplate['content'], [
                        'NAME' => ucwords($payment->name),
                        'MERCHANT' => $webfront->user->name,
                        'WEBFRONT_TITLE' => $webfront->title,
                        'BILL_AMOUNT' => formatPrice($billAmount),
                        'INVOICE_NO' => formatInvoiceNo($payment->id),
                        'PAYMENT_LINK' => "<a href='{$paymentLink}'>{$paymentLink}</a>",
                        'PAYMENT_LINK_BTN' => $paymentLinkBtn,
                        'VIEW_TRANSACTION_LINK' => "<a href='{$viewTransLink}'>{$viewTransLink}</a>",
                        'SITE_NAME' => "<a href='" . HTTP_ROOT. "'>" . SITE_NAME. "</a>",
                    ]);                        
                        
                    $this->SendEmail->sendEmail($payment->email, $mailTemplate->subject, $message);

                    // Update followup_counter
                    $this->Payments->query()->update()->set(['followup_counter' => `followup_counter` + 1])->where(['id' => $payment->id])->execute();
                }
            }
        } catch (\Exception $ex) {
            
        }
    }

    public function resendEmail($uniqID) {  
        $query = $this->Payments->find('all')->where(['Payments.uniq_id' => $uniqID]);
        if ($query->count()) {

            $payment = $query->first();
          
            $this->Payments->sendEmail($payment->id, 'PAYMENT_NOTIFICATION');
            
            $this->Flash->success(__('Email Resent Successfully!!'));
            
        } else {
            $this->Flash->error(__('Failed to send email. Invoice Not Found!!'));
        }
        return $this->redirect($this->referer());
    }

    public function webfrontEmailTemplates($uniqID) {
        $webfront = $this->Webfronts->find()->where(['unique_id' => $uniqID])->select(['id', 'title'])->first();
        $this->MailTemplates->generateWebfrontTemplates($webfront->id);
        $emailTemplates = $this->MailTemplates->find()->where(['webfront_id' => $webfront->id])->order(['name' => 'ASC']);

        $pageHeading = $webfront->title . ' Mail Templates';
        $this->set(compact(['emailTemplates', 'pageHeading']));
    }

    public function editEmailTemplate($uniqueID) {
        $template = $this->MailTemplates->find()->where(['unique_id' => $uniqueID])->first();

        if ($this->request->is('put')) {

            $data = $this->request->getData();

            $this->MailTemplates->patchEntity($template, $data);
            if ($this->MailTemplates->save($template)) {
                $this->Flash->success(__('Template Updated Successfully'));
                return $this->redirect($this->referer());
            }
            $this->Flash->error(__('Template Updation Failed!!'));
        }

        $pageHeading = 'Edit Template';
        $this->set(compact(['pageHeading', 'template']));
    }
    
    public function deleteInvoices() {
        if ($this->request->is('post')) {
            $data = $this->request->getData();

            $this->Payments->deleteAll(['uniq_id IN' => $data['delete_invoices'], 'status' => 0]);

            $this->UploadedPaymentFiles->updateCustomerCount($this->merchantId);

            $this->Flash->success(__('Selected Invoices Deleted!!'));
        }
        return $this->redirect($this->referer());
    }

}
