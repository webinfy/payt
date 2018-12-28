<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * SendEmail component
 */
class SendEmailComponent extends Component {

    function __construct($prompt = null) {
        
    }

    public function sendEmail($to, $subject, $message, $attachments = array()) {
        try {
            $mail = new PHPMailer(true);

            // Server Settings
            $mail->SMTPDebug = 0;                               // Enable verbose debug output
            $mail->isSMTP(true);                                // Set mailer to use SMTP
            $mail->Host = 'smtp.sparkpostmail.com';             // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                             // Enable SMTP authentication
            $mail->Username = 'SMTP_Injection';                 // SMTP username
            $mail->Password = '1c923449f73ed782ecf14cec88b3ad2f9e700aae'; // SMTP password
            $mail->SMTPSecure = 'tls';                          // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587;                                  // TCP port to connect to

            $mail->setFrom(FROM_EMAIL, SITE_NAME);
            $mail->AddReplyTo(FROM_EMAIL, SITE_NAME);
            $mail->addAddress($to);
            $mail->AddBCC(BCC_EMAIL);

            $siteUrl = HTTP_ROOT;
            $logoLink = '<img alt="PayTring" src="' . HTTP_ROOT . 'images/logo.png"/>';
            $mailBody = <<<MAILCONTECT
                <table style='background: #fff none repeat scroll 0 0;border: 1px solid #e9e9e9;border-radius: 3px;width: 600px;font-family: sans-serif;' cellpadding='0' cellspacing='0'>
                   <tr>
                    <td class='padding: 20px;box-sizing: border-box;font-size: 14px;margin: 0;'>
                        <table cellpadding='0' cellspacing='0' style='width:100%;'>
                            <tr><td style='padding: 10px 0px;text-align: center;background: #303f9f;float: left;width: 100%;'>{$logoLink}</td></tr>
                            <tr><td style='padding:5px 10px 20px 15px; font-size:14px'>{$message}</td></tr>
                        </table>
                    </td>
                    </tr>
                </table>          
MAILCONTECT;

            $mail->Subject = $subject;
            $mail->Body = $mailBody;
            $mail->IsHTML(true);

            // Atatach files to the email if present
            if (!empty($attachments) && count($attachments) > 0) {
                foreach ($attachments as $file) {
                    $mail->addAttachment($file);
                }
            }

            if (LIVE) {
                if ($mail->send()) {
                    return TRUE;
                }
            } else {
                // Dump email content to a server directory. Usefull during developement. Disable during production.
                $emailContentFile = md5(microtime()) . rand(1111, 9999) . ".html";
                file_put_contents("tempemails/$emailContentFile", $mailBody, FILE_USE_INCLUDE_PATH);
            }

            //mail($to, $subject, $message);
        } catch (Exception $ex) {
            
        }
        return FALSE;
    }

}
