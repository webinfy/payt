<?php

namespace App\Shell;

use Cake\Console\Shell;
use Cake\Mailer\Email;

class PaymentShell extends Shell {

    public function main() {
        $this->out('Jay Jagannath.........');
    }

    /*
     * Developer   :  Pradeepta Kumar Khatoi
     * Date        :  18th Dec 2018
     * Description :  Send SMS to customer if invoice not paid yet.
     */

    public function dueDateNotifications() {

        $this->loadModel('Payments');

        $currentDate = date("Y-m-d");
        $twoDayBefore = date('Y-m-d', strtotime($currentDate . ' +2 day'));
        $oneDayBefore = date('Y-m-d', strtotime($currentDate . ' +1 day'));

        $conditions = [
            'Payments.status' => 0,
            'OR' => [
                ['UploadedPaymentFiles.payment_cycle_date' => $twoDayBefore],
                ['UploadedPaymentFiles.payment_cycle_date' => $oneDayBefore]
            ]
        ];

        $payments = $this->Payments->find('all')->where($conditions)->contain(['UploadedPaymentFiles']);

        foreach ($payments as $payment) {
            if (!empty($payment->phone) && strlen($payment->phone) == 10) {
                $invoiceLink = HTTP_ROOT . "preview-invoice/" . $payment->uniq_id;
                $sms = "Use this Below Link to process Payment of amount INR {$payment->fee}." . " Your due date is " . date_format($payment->uploaded_payment_file->payment_cycle_date, 'd M, Y') . ". " . $invoiceLink;
                sendSMS($payment->phone, $sms);
            }
        }

        $this->out('Notification Sent Successfully');
        exit;
    }

}
