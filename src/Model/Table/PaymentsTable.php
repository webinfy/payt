<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Controller\Component\CustomComponent;
use App\Controller\Component\SendEmailComponent;
use Cake\ORM\TableRegistry;

class PaymentsTable extends Table {

    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('payments');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->addBehavior('CounterCache', [
            'UploadedPaymentFiles' => ['upload_count']
        ]);

        $this->belongsTo('Webfronts', [
            'foreignKey' => 'webfront_id',
            'joinType' => 'LEFT'
        ]);

        $this->belongsTo('UploadedPaymentFiles', [
            'foreignKey' => 'uploaded_payment_file_id',
            'joinType' => 'LEFT'
        ]);

        $this->belongsTo('Users', [
            'foreignKey' => 'customer_id',
            'joinType' => 'LEFT'
        ]);
    }

    public function validationDefault(Validator $validator) {

        $validator
                ->requirePresence('name', 'create')
                ->notEmpty('name');

        $validator
                ->email('email')
                ->requirePresence('email', 'create')
                ->notEmpty('email');

        $validator
                ->requirePresence('phone', 'create')
                ->notEmpty('phone');
//
//        $validator
//                ->decimal('convenience_fee_amount')
//                ->requirePresence('convenience_fee_amount', 'create')
//                ->notEmpty('convenience_fee_amount');
//
//        $validator
//                ->decimal('fee')
//                ->requirePresence('fee', 'create')
//                ->notEmpty('fee');


        return $validator;
    }

//    public function buildRules(RulesChecker $rules) {      
//        $rules->add($rules->existsIn(['uploaded_payment_file_id'], 'UploadedPaymentFiles'));
//        $rules->add($rules->existsIn(['customer_id'], 'Users'));
//
//        return $rules;
//    }

    public function sendEmail($paymentID, $templateName, $data = [], $uniqID = NULL) {

        $query = $this->find()->where(['Payments.id' => $paymentID]);

        if ($query->count()) {

            $this->AdminSettings = TableRegistry::getTableLocator()->get('AdminSettings');
            $this->MailTemplates = TableRegistry::getTableLocator()->get('MailTemplates');
            $this->Webfronts = TableRegistry::getTableLocator()->get('Webfronts');
            $this->SendEmail = new SendEmailComponent();

            $adminSetting = $this->AdminSettings->find()->where(['id' => 1])->first();

            $mailTemplate = $this->MailTemplates->find()->where(['name' => $templateName, 'is_active' => 1])->first();
            $message = $mailTemplate->content;

            switch ($templateName) {

                case 'PAYMENT_NOTIFICATION':

                    $payment = $query->contain(['Webfronts.Users'])->first();

                    $billAmount = $payment->fee;
                    $paymentLink = HTTP_ROOT . "preview-invoice/" . $payment->uniq_id;
                    $paymentLinkBtn = "<a style='background: none repeat scroll 0 0 #C20E09; border-radius: 4px;color: #FFFFFF;display: block;font-size: 14px; font-weight: bold;margin: 15px 1px;padding: 8px 10px;text-align: center;width: 130px;text-decoration:none;' href='{$paymentLink}'>Preview Invoice</a>";
                    $viewTransLink = HTTP_ROOT . "webfront/" . $payment->webfront->url . "?viewTxn=" . $payment->reference_number;

                    $message = str_replace("[NAME]", ucwords($payment->name), $message);
                    $message = str_replace("[MERCHANT]", $payment->webfront->user->name, $message);
                    $message = str_replace("[WEBFRONT_TITLE]", $payment->webfront->title, $message);
                    $message = str_replace("[BILL_AMOUNT]", formatPrice($billAmount), $message);
                    $message = str_replace("[INVOICE_NO]", formatInvoiceNo($payment->id), $message);
                    $message = str_replace("[PAYMENT_LINK]", "<a href='{$paymentLink}'>{$paymentLink}</a>", $message);
                    $message = str_replace("[PAYMENT_LINK_BTN]", $paymentLinkBtn, $message);
                    $message = str_replace("[VIEW_TRANSACTION_LINK]", "<a href='{$viewTransLink}'>{$viewTransLink}</a>", $message);
                    $message = str_replace("[SITE_NAME]", "<a href='" . HTTP_ROOT. "'>" . SITE_NAME. "</a>", $message);

                    $this->SendEmail->sendEmail($payment->email, $mailTemplate->subject, $message);

                    $this->query()->update()->set(['followup_counter' => `followup_counter` + 1])->where(['id' => $payment->id])->execute();
                    
                    break;
                
                case 'PAYMENT_CONFIRMATION':

                    $payment = $query->contain(['Webfronts.Users'])->first();
                    $text_link = HTTP_ROOT . "preview-invoice/" . $payment->uniq_id;
                    $btnLink = "<a target='_blank' style='background: none repeat scroll 0 0 #C20E09; border-radius: 4px;color: #FFFFFF;display: block;font-size: 14px; font-weight: bold;margin: 15px 1px;padding: 8px 10px;text-align: center;width: 130px;text-decoration:none;' href='" . $text_link . "'>Dwonload Receipt</a>";

                    $message = str_replace("[NAME]", ucwords($payment->name), $message);
                    $message = str_replace("[WEBFRONT_NAME]", $payment->webfront->title, $message);
                    $message = str_replace("[MERCHANT]", $payment->webfront->user->name, $message);
                    $message = str_replace("[PAYMENT_DATE]", date_format($payment->payment_date, 'd M, Y'), $message);
                    $message = str_replace("[PAID_AMOUNT]", 'Rs.'. formatPrice($payment->paid_amount), $message);
                    $message = str_replace("[INVOICE_NO]", formatInvoiceNo($payment->id), $message);
                    $message = str_replace("[INVOICE_LINK_BTN]", $btnLink, $message);
                    $message = str_replace("[SITE_NAME]", "<a href='" . HTTP_ROOT. "'>" . SITE_NAME. "</a>", $message);
                    
                    $this->SendEmail->sendEmail($payment->email, $mailTemplate->subject, $message);

                    break;

                case 'PAYMENT_FAILURE':

                    $payment = $query->contain(['Webfronts', 'Webfronts.Users', 'UploadedPaymentFiles'])->first();
                    
                    $text_link = $this->Webfronts->get($payment->webfront_id)->short_url;
                    $link = "<a target='_blank' style='background: none repeat scroll 0 0 #C20E09; border-radius: 4px;color: #FFFFFF;display: block;font-size: 14px; font-weight: bold;margin: 15px 1px;padding: 5px 10px;text-align: center;width: 232px;text-decoration:none;' href='" . $text_link . "'>Paid on this link</a>";

                    $message = str_replace("[MERCHANT]", ucwords($payment->webfront->user->name), $message);
                    $message = str_replace("[WEBFRONT_TITLE]", ucwords($payment->webfront->title), $message);
                    $message = str_replace("[NAME]", ucwords($payment->name), $message);
                    $message = str_replace("[EMAIL]", ucwords($payment->email), $message);
                    $message = str_replace("[PHONE]", ucwords($payment->phone), $message);
                    $message = str_replace("[BILL_AMOUNT]", $payment->fee, $message);
                    $message = str_replace("[PAYMENT_CYCLE_DATE]", date("d M, Y", strtotime($payment->uploaded_payment_file->payment_cycle_date)), $message);
                    $message = str_replace("[SUPPORT_EMAIL]", SUPPORT_EMAIL , $message);
                    $message = str_replace("[SITE_NAME]", SITE_NAME , $message);
                    
                    $this->SendEmail->sendEmail($payment->email, $mailTemplate->subject, $message);

                    break;

                default:
            }
        }
    }

    public function geterateInvoicePdf($uniqueID) {
        try {
            $this->Custom = new CustomComponent;
            $this->Custom->getUrlContent(HTTP_ROOT . "preview-invoice/{$uniqueID}?pdf=true");
        } catch (\Exception $ex) {
            
        }
    }

}
