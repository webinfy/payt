<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Cake\Http\Exception\NotFoundException;

class WebfrontsController extends AppController {

    public function initialize() {
        parent::initialize();

        // Load Models
        $this->loadModel('Webfronts');
        $this->loadModel('Users');
        $this->loadModel('Merchants');
        $this->loadModel('Payments');
        $this->loadModel('Validations');
        $this->loadModel('UploadedPaymentFiles');
        $this->loadModel('PaymentGatewayResponses');

        // Load Components
        $this->loadComponent('Custom');
        $this->loadComponent('Paginator');

        // Set Layout
        $this->viewBuilder()->setLayout('');
    }

    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        $authAllow = ['index', 'viewWebfront', 'previewInvoice', 'payuResponse', 'razorPayResponse', 'payNow', 'downloadReceipt', 'payuAdvWebfrontForm', 'ajaxCheckEmailAvail'];
        $this->Auth->allow($authAllow);
    }

    /*
     * Developer    : Pratap Kishore Swain (pratap.raddyx@gmail.com)
     * Created      : 22nd Nov 2018
     * Description  : Used for both website home page & merchant profile page. 
     * For home page display all published webfronts & for merchant profile show all merchant specific webfront which are public. 
     */

    public function index($profileUrl = NULL) {
        $this->viewBuilder()->setLayout('');

        // Get Merchant details if $profileUrl is present.
        $merchant = NULL;
        if ($profileUrl) {
            $query = $this->Merchants->find()->where(['profile_url' => $profileUrl])->contain(['Users']);
            if ($query->count() == 0) {
                throw new NotFoundException("Invalid URL!!");
            }
            $merchant = $query->first();
        }

        $conditions = ['Webfronts.is_published' => 1];

        // Show merchat specific webfronts.
        if (!empty($merchant)) {
            $conditions[] = ['Webfronts.merchant_id' => $merchant->user_id, 'Webfronts.is_public' => 1];
        }

        if (!empty($_REQUEST['search'])) {
            $serach = trim(urldecode($_REQUEST['search']));
            $conditions[] = ['Webfronts.title LIKE' => "%" . $serach . "%"];
        }

        $fields = ['title', 'url', 'logo', 'address', 'phone', 'email', 'merchant_name' => 'Users.name'];
        $config = [
            'contain' => ['Users'],
            'conditions' => $conditions,
            'fields' => $fields,
            'order' => ['Webfronts.id' => 'DESC'],
            'limit' => 16
        ];

        $webfronts = $this->Paginator->paginate($this->Webfronts->find(), $config);
        $this->set(compact('webfronts'));
    }

    /*
     * Developer   :  Paratap Kishore Swain
     * Date        :  17th Oct 2018
     * Description :  View webfront public page.
     */

    public function viewWebfront($url) {
        
        $contain = ['Merchants.MerchantPaymentGateways', 'Users', 'WebfrontFields', 'WebfrontFields.WebfrontFieldValues', 'WebfrontPaymentAttributes'];
        $query = $this->Webfronts->find()->where(['Webfronts.url' => $url])->contain($contain);
               
        if ($query->count()) {
            $webfront = $query->first();       
        } else {
            throw \Cake\Datasource\Exception\PageOutOfBoundsException;
        }

        $recentInvoices = NULL;
        if (isset($_REQUEST['ref_no'])) {
            $reference_number = trim($_REQUEST['ref_no']);
            $conditions = ['Payments.reference_number' => $reference_number, 'Payments.webfront_id' => $webfront->id];
            $recentInvoices = $this->Payments->find('all')->where($conditions)->contain(['UploadedPaymentFiles'])->order(['Payments.status' => 'ASC', 'UploadedPaymentFiles.payment_cycle_date' => 'DESC']);
        }

        // Fetch Recent Payments
        $recentPayments = NULL;
        if ($webfront->show_recent_payments) {
            $recentPayments = $this->Payments->find()->where(['webfront_id' => $webfront->id, 'status' => 1])->order(['payment_date' => 'DESC']);
        }

        // Get all validations
        $validations = $this->Validations->find()->select(['id', 'reg_exp', 'err_msg']);

        $this->set(compact(['webfront', 'validations', 'recentPayments', 'recentInvoices']));
    }
        
    public function payNow($url) {

        if ($this->request->is('post')) {

            $data = $this->request->getData();

            $webfront = $this->Webfronts->find()->where(['url' => $url])->first();

            $payment = $this->Payments->newEntity();

            $customerFields = [];
            if (isset($data['payee_custom_fields'])) {
                foreach ($data['payee_custom_fields'] as $key => $value) {
                    $customerFields[$key] = [
                        'field' => $key,
                        'value' => $value
                    ];
                }
                $data['payee_custom_fields'] = json_encode($customerFields);
            } else {
                $data['payee_custom_fields'] = NULL;
            }



            $paymentFields = [];
            if (isset($data['payment_custom_fields'])) {
                foreach ($data['payment_custom_fields'] as $key => $value) {
                    if (!empty($data['payment_custom_fields'][$key])) {
                        $paymentFields[$key] = [
                            'field' => $key,
                            'value' => $value
                        ];
                    }
                }
                $data['payment_custom_fields'] = json_encode($paymentFields);
            } else {
                $data['payment_custom_fields'] = NULL;
            }


            $this->Payments->patchEntity($payment, $data);

            $payment->uniq_id = $this->Custom->generateUniqId();
            $payment->webfront_id = $webfront->id;
            $payment->payment_date = date("Y-m-d");

            if ($this->Payments->save($payment)) {
                return $this->redirect(HTTP_ROOT . 'preview-invoice/' . $payment->uniq_id);
            } else {
                $this->Flash->error(__('Error Occured, Please Try Again'));
                $this->redirect($this->referer());
            }
        }
    }
       
    public function ajaxCheckEmailAvail() {
        $this->viewBuilder()->setLayout('ajax');
        $email = $this->request->getData('email');
        $emailExist = $this->Users->find()->where(['email' => $email])->count();
        if ($emailExist > 0) {
            echo "false";
        } else {
            echo "true";
        }
        exit;
    }
    
    /*
     * Developer   :  Paratap Kishore Swain
     * Date        :  22nd Nov 2018
     * Description :  View & Download selected Advance Webfront Report.
     */

    public function advanceWebfrontReports() {
        $this->viewBuilder()->setLayout('default');  
        
        // Filter blank data & convert get to post request
        if ($this->request->is('post')) {
            $params = array_filter($this->request->getData());
            return $this->redirect(['controller' => 'Webfronts', 'action' => 'advanceWebfrontReports', '?' => $params]);
        }

        $queryParams = $this->request->getQueryParams();
        $webfrontID = NULL;
        
        if (isset($queryParams['webfront_id'])) {

            $webfrontID = $queryParams['webfront_id'];
            $conditions = ['Payments.webfront_id' => $webfrontID];            
            
            if (!empty($queryParams['date'])) {
                $conditions[] = ['DATE(Payments.created)' => $queryParams['date']];
            }

            if (!empty($queryParams['keyword'])) {
                $keyword = trim(urldecode($queryParams['keyword']));
                $conditions[] = ['OR' => ['Payments.name' => $keyword, 'Payments.email' => $keyword, 'Payments.phone' => $keyword]];
            }
            
            if (isset($queryParams['status'])) {
                $status = $queryParams['status'] == 1 ? 1 : 0;
                $conditions[] = ['Payments.status' => $status];
            }
            
            $webfront = $this->Webfronts->find()->where(['Webfronts.id' => $webfrontID])->contain(['WebfrontFields', 'WebfrontPaymentAttributes'])->first();
            $payments = $this->Payments->find()->where($conditions)->order(['Payments.id' => 'DESC', 'name' => 'ASC']);

            // Download Report Start
            if (isset($queryParams['download'])) {

                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();
                
                $alphabets = range('A', 'Z');
                
                $styleArrayTitle = [
                    'font' => ['bold' => true],
                    'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
                ];

                $headers = ['Sl. No.', 'Name', 'Email', 'Phone'];
                $customFields = false;

                foreach ($webfront->webfront_fields as $field) {
                    $headers[] = ucwords($field->name);
                }
                foreach ($webfront->webfront_payment_attributes as $field) {
                    $headers[] = ucwords($field->name);
                }
                $headers[] = 'Total Amount';
                $headers[] = 'Payment Status';
                $headers[] = 'Paid Via';
                $headers[] = 'Payment Date';
                $headers[] = 'Transaction ID';               
               
                $numOfCols = count($headers);
                $sheet->fromArray($headers, NULL, 'A1');

                $i = 1;
                $sheetData = [];
                foreach ($payments as $payment) {
                    $sheetData[$i][] = $i;                    
                    $sheetData[$i][] = $payment->name;
                    $sheetData[$i][] = $payment->email;
                    $sheetData[$i][] = $payment->phone;

                    // Custom Customer Fields
                    $payee_custom_fields = json_decode($payment->payee_custom_fields);
                    foreach ($payee_custom_fields as $field) {
                        $sheetData[$i][] = !empty($field->value) ? $field->value : ' ';
                    }

                    // Custom Payment Fields
                    $payment_custom_fields = json_decode($payment->payment_custom_fields);
                    foreach ($payment_custom_fields as $field) {
                        $sheetData[$i][] = !empty($field->value) ? $field->value : ' ';
                    }

                    $sheetData[$i][] = !empty($payment->paid_amount) ? $payment->paid_amount : '';
                    $sheetData[$i][] = ($payment->status == 1) ? 'Paid' : 'Not Paid';
                    $sheetData[$i][] = ($payment->status == 1) ? $payment->paid_via : 'Not Paid';
                    $sheetData[$i][] = ($payment->payment_date && $payment->payment_date != '0000-00-00') ? date_format($payment->payment_date, "d M, Y") : '';
                    $sheetData[$i][] = !empty($payment->txn_id) ? $payment->txn_id : '';
                    $i++;
                }
                $sheet->fromArray($sheetData, NULL, 'A2');

                for ($j = 0; $j < $numOfCols; $j++) {
                    $sheet->getColumnDimension($alphabets[$j])->setAutoSize(true);
                    $sheet->getStyle($alphabets[$j] . '1')->applyFromArray($styleArrayTitle);
                    $sheet->getStyle($alphabets[$j])->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                }

                $writer = new Xlsx($spreadsheet);
                $file = "PaymentReport-" . time() . rand(1111, 9999) . ".xlsx";
                $writer->save(WWW_ROOT . 'files/reports/' . $file);

                $filePath = WWW_ROOT . "files/reports/" . DS . $file;
                $response = $this->response->withFile($filePath, ['download' => TRUE, 'name' => $file]);
                return $response;
            }
            // Download Report End
            
            $config = ['limit' => 10];
            $payments = $this->Paginator->paginate($payments, $config);
        }

        $pageHeading = 'Advance Webfront Reports';
        $webfrontList = $this->Webfronts->find('list', ['keyField' => 'id', 'valueField' => 'url'])->where(['merchant_id' => $this->merchantId,'type' => 1]);
        
        $this->set(compact(['webfrontList', 'payments', 'webfrontID', 'pageHeading']));
    }


    /*
     * Developer   :  Paratap Kishore Swain
     * Date        :  22nd Nov 2018
     * Description :  View & Download selected Basic Webfront Report.
     */

    public function basicWebfrontReports() {
        $this->viewBuilder()->setLayout('default');
        $pageHeading = 'Basic Webfront Reports';
        
        // Filter blank data & convert get to post request
        if ($this->request->is('post')) {
            $params = array_filter($this->request->getData());
            return $this->redirect(['controller' => 'Webfronts', 'action' => 'basicWebfrontReports', '?' => $params]);
        }

        $webfrontId = NULL;
        $queryParams = $this->request->getQueryParams();
        $payments = NULL;
                
        if (isset($queryParams['webfront_id'])) {
            
            $webfrontId = $queryParams['webfront_id'];
            $conditions = ['Payments.webfront_id' => $queryParams['webfront_id']];

            if (!empty($queryParams['file_id'])) {
                $conditions[] = ['Payments.uploaded_payment_file_id' => $queryParams['file_id']];
            }

            if (!empty($queryParams['due_date'])) {
                $conditions[] = ['UploadedPaymentFiles.payment_cycle_date' => $queryParams['due_date']];
            }

            if (!empty($queryParams['keyword'])) {
                $keyword = trim(urldecode($queryParams['keyword']));
                $conditions[] = ['OR' => ['Payments.name' => $keyword, 'Payments.email' => $keyword, 'Payments.phone' => $keyword]];
            }
            
            if (isset($queryParams['status'])) {
                $status = $queryParams['status'] == 1 ? 1 : 0;
                $conditions[] = ['Payments.status' => $status];
            }

            $webfront = $this->Webfronts->find()->where(['Webfronts.id' => $webfrontId])->contain(['WebfrontFields', 'WebfrontPaymentAttributes'])->first();
            $payments = $this->Payments->find()->where($conditions)->contain(['UploadedPaymentFiles'])->order(['Payments.id' => 'DESC', 'Payments.name' => 'ASC']);
                       
            if (isset($queryParams['download'])) {
                
                $objPHPExcel = new Spreadsheet();
                $sheet = $objPHPExcel->getActiveSheet();

                $count = 0;
                $head = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];

                $sheet->getStyle("A1:{$head[$count]}1")->getAlignment()->applyFromArray([
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_FILL,
                ]);

                $sheet->getStyle("A1:{$head[$count]}1")->applyFromArray([
                    'font' => ['name' => 'Cambria', 'bold' => true, 'italic' => false, 'strikethrough' => false, 'color' => ['rgb' => '7, 7, 7']],
                    'quotePrefix' => true
                ]);

                foreach (range('A', 'L') as $columnID) {
                    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
                }
                
                // SetHeading                 
                $headers = ['Sl. No.', 'Invoice Number', 'Name', 'Email', 'Phone', 'Ref No.'];
                foreach ($webfront->webfront_fields as $field) {
                    $headers[] = ucwords($field->name);
                }
                foreach ($webfront->webfront_payment_attributes as $field) {
                    $headers[] = ucwords($field->name);
                }
                $headers[] = 'Total Amount';                
                $headers[] = 'Payment Status';
                $headers[] = 'Paid Via';
                $headers[] = 'Payment Date';
                $headers[] = 'Transaction ID';

                $numOfCols = count($headers);
                $sheet->fromArray($headers, NULL, 'A1');

                // Set Content
                $i = 1;
                foreach ($payments as $payment) {                    
                    $sheetData[$i][] = $i;
                    $sheetData[$i][] = sprintf('%04d', $payment->id);
                    $sheetData[$i][] = $payment->name;
                    $sheetData[$i][] = $payment->email;
                    $sheetData[$i][] = $payment->phone;
                    $sheetData[$i][] = $payment->reference_number;
                    
                    // Custom Customer Fields
                    $payee_custom_fields = json_decode($payment->payee_custom_fields);
                    foreach ($payee_custom_fields as $field) {
                        $sheetData[$i][] = !empty($field->value) ? $field->value : ' ';
                    }

                    // Custom Payment Fields
                    $payment_custom_fields = json_decode($payment->payment_custom_fields);
                    foreach ($payment_custom_fields as $field) {
                        $sheetData[$i][] = !empty($field->value) ? $field->value : ' ';
                    }

                    $sheetData[$i][] = $payment->fee;
                    $sheetData[$i][] = ($payment->status == 1) ? 'Paid' : 'Not Paid';
                    $sheetData[$i][] = ($payment->status == 1) ? $payment->paid_via : 'Not Paid';
                    $sheetData[$i][] = ($payment->payment_date && $payment->payment_date != '0000-00-00') ? date_format($payment->payment_date, "d M, Y") : '';
                    $sheetData[$i][] = !empty($payment->txn_id) ? $payment->txn_id : '';                   

                    $i++;
                }
                $sheet->fromArray($sheetData, NULL, 'A2');

                $writer = new Xlsx($objPHPExcel);
                $fileName = "Transaction-Report-" . time() . ".xlsx";
                $filePath = WWW_ROOT . 'files/reports/' . DS . $fileName;
                $writer->save($filePath);

                $response = $this->response->withFile($filePath, ['download' => true, 'name' => $fileName]);
                return $response;
            }
            
            $config = ['limit' => 10];
            $payments = $this->Paginator->paginate($payments, $config);
        }

        $webfrontList = $this->Webfronts->find('list', ['keyField' => 'id', 'valueField' => 'url'])->where(['Webfronts.merchant_id' => $this->merchantId, 'Webfronts.type' => 0]);
        $this->set(compact(['pageHeading', 'webfrontList', 'webfront', 'webfrontId', 'payments']));
    }    
    
    
    public function downloadQrCode($id) {
        
        $qrCode = $this->Webfronts->get($id)->qr;
        
        $filePath = WWW_ROOT . "files/qr/" . DS . $qrCode;
        $response = $this->response->withFile($filePath, ['download' => TRUE, 'name' => $qrCode]);
        return $response;
        
    }
    
    /*
     * Developer   :  Pradeepta Kumar Khatoi
     * Date        :  21st Dec 2018
     * Description :  Update Invoice Payment Status.
     */

    public function updatePaymentStatus($uniqId, $status) {
        $payment = $this->Payments->find()->where(['uniq_id' => $uniqId])->first();
        $payment->status = $status;
        $payment->paid_via = 'Offline';

        if ($this->Payments->save($payment)) {
            $msg = $status == 1 ? 'Invoice status marked as Paid!!' : 'Invoice status marked as Not Paid!!';
            $this->Flash->success(__($msg));
        } else {
            $this->Flash->error(__('Failed to update payment status. Please try later!!'));
        }
        return $this->redirect($this->referer());
    }

    public function editInvoice($uniqID) {
        $this->viewBuilder()->setLayout('');
        $payment = $this->Payments->find()->where(['uniq_id' => $uniqID])->contain(['Webfronts'])->first();
        $this->set(compact('payment'));
    }

    public function ajaxEditInvoice() {
        $this->viewBuilder()->setLayout('');

        if ($this->request->is(['post'])) {

            $data = $this->request->getData();

            $payment = $this->Payments->find()->where(['uniq_id' => $data['uniq_id']])->first();
            $payment->fee = $data['fee'];
            $paymentFields = [];
            foreach ($data['payment_custom_fields'] as $index => $customField) {
                $key = key($customField);
                $paymentFields[] = [
                    'field' => $key,
                    'value' => $customField[$key],
                ];
            }
            $payment->payment_custom_fields = json_encode($paymentFields);

            if ($this->Payments->save($payment)) {
                
                // Send Email To Customer
                $this->Payments->sendEmail($payment->id, 'PAYMENT_NOTIFICATION');
                
                echo 'success';
            } else {
                echo 'error';
            }
        }
        exit;
    }

}
