<?php

namespace App\Controller\Component;

use Cake\Mailer\Email;
use Cake\Controller\Component;
use Endroid\QrCode\QrCode;
use Mremi\UrlShortener\Model\Link;
use Mremi\UrlShortener\Provider\Bitly\BitlyProvider;
use Mremi\UrlShortener\Provider\Bitly\GenericAccessTokenAuthenticator;
use Mremi\UrlShortener\Model\LinkManager;
use Mremi\UrlShortener\Provider\ChainProvider;

class CustomComponent extends Component {

    function __construct($prompt = null) {
        
    }

    /*
     * This function is used to calculate late fee on an invoice. 
     * Late fee is calculate using below 3 days 
     * Type 1: Fix amount after due date 
     * Type 2: Late Fee amount multiplied by recurring_period
     * Type 3: Amount X after date 1 & amount Y if date 2
     */

    public function calLateFee($payment) {
        try {

            $late_fee_amount = 0;

            // For Basic Webfront get due date from file upload table & for advance webfront get from webfront level.
            $due_date = ($payment->webfront->type == 0) ? $payment->uploaded_payment_file->payment_cycle_date : $payment->webfront->payment_cycle_date;

            // If (Current Date > Due Date) { Calculate Late Fee. }
            if (date('Y-m-d') > date('Y-m-d', strtotime($due_date))) {

                $late_fee_amount = $payment->webfront->late_fee_amount;

                if ($payment->webfront->late_fee_type == 3) { // Perodic Late Fee
                    $today_date = date("Y-m-d");
                    $p1_date = date('Y-m-d', strtotime($due_date . " +{$payment->webfront->periodic_days_1} days"));
                    $p2_date = date('Y-m-d', strtotime($due_date . " +{$payment->webfront->periodic_days_2} days"));

                    if (strtotime($today_date) > strtotime($p2_date)) {
                        $late_fee_amount = $payment->webfront->periodic_amount_2;
                    } elseif (strtotime($today_date) > strtotime($p1_date)) {
                        $late_fee_amount = $payment->webfront->periodic_amount_1;
                    } else {
                        $late_fee_amount = 0; // During Grace Period (payment_cycle_date < Curr Date < periodic_days_1) Late Not Applied
                    }
                } else if ($payment->webfront->late_fee_type == 2) { // Recurring Late Fee
                    $days = floor((time() - strtotime($payment->uploaded_payment_file->payment_cycle_date)) / (60 * 60 * 24));
                    $days = ceil($days / $payment->webfront->recurring_period);
                    $late_fee_amount = $payment->webfront->late_fee_amount * $days;
                } else if ($payment->webfront->late_fee_type == 1) { // Fixed Late Fee
                    $late_fee_amount = $payment->webfront->late_fee_amount;
                }

                return $late_fee_amount;
            }
        } catch (\Exception $ex) {
            
        }
        return 0;
    }

    public function getNewFileName($path, $fileName = 'file') {
        $newfilename = NULL;
        $ext = $this->getExtension($fileName);
        if ($fileName && !file_exists($path . $fileName) && strlen($fileName) < 150) {
            $newfilename = $fileName;
        } else {
            $filenamesubstr = substr(pathinfo($fileName, PATHINFO_FILENAME), 0, 100);
            $newfilename = $filenamesubstr . "-" . time() . rand(1000, 9999) . "." . $ext;
        }
        return $newfilename;
    }

    function formatForgotPassword($msg, $name, $link_text, $link) {
        if (strstr($msg, "[NAME]")) {
            $msg = str_replace("[NAME]", $name, $msg);
        }
        if (strstr($msg, "[LINK_TEXT]")) {
            $msg = str_replace("[LINK_TEXT]", "<a href='{$link_text}'>" . $link_text . "</a>", $msg);
        }
        if (strstr($msg, "[LINK]")) {
            $msg = str_replace("[LINK]", $link, $msg);
        }
        if (strstr($msg, "[SITE_NAME]")) {
            $msg = str_replace("[SITE_NAME]", "<a href='" . HTTP_ROOT . "'>" . SITE_NAME . "</a>", $msg);
        }
        return $msg;
    }

    function generateUniqId($id = NULL) {
        $uniq = uniqid(rand()) . uniqid(rand());
        if ($id) {
            return md5($uniq . time() . $id);
        } else {
            return md5($uniq . time());
        }
    }

    function generateUniqNumber($id = NULL) {
        $uniq = uniqid(rand());
        if ($id) {
            return md5($uniq . time() . $id);
        } else {
            return md5($uniq . time());
        }
    }

    public function generateOTP() {
        return rand(111111, 999999);
    }

    public function encrypt($text) {
        $text = urlencode(base64_encode($text));
        return $text;
    }

    public function decrypt($text) {
        $text = base64_decode(urldecode($text));
        return $text;
    }

    public function file_get_contents_curl($url) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }

    function uploadFile($tmpName, $name, $path) {
        if ($name) {
            $file = strtolower($name);
            $fileExtention = $this->getExtension($file); //$extname = substr(strrchr($image, "."), 1);            
            if (!in_array($fileExtention, ['gif', 'jpg', 'jpeg', 'png', 'bmp', 'doc', 'docx', 'pdf'])) {
                return false;
            } else {
                if (!is_dir($path)) {
                    mkdir($path);
                }
                $fileName = md5(time() . rand(100, 999)) . "." . $fileExtention;
                move_uploaded_file($tmpName, "$path/$fileName");
                return $fileName;
            }
        }
    }

    function uploadImagex($tmp_name, $name, $path, $imgWidth) {
        if ($name) {
            $image = strtolower($name);
            $extname = $this->getExtension($image); //$extname = substr(strrchr($image, "."), 1);
            if (($extname != 'gif') && ($extname != 'jpg') && ($extname != 'jpeg') && ($extname != 'png') && ($extname != 'bmp')) {
                return false;
            } else {
                if ($extname == "jpg" || $extname == "jpeg") {
                    $src = imagecreatefromjpeg($tmp_name);
                } else if ($extname == "png") {
                    $src = imagecreatefrompng($tmp_name);
                } else {
                    $src = imagecreatefromgif($tmp_name);
                }
                list($width, $height) = getimagesize($tmp_name);

                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }

                if ($extname == 'gif' || $width <= $imgWidth) {
                    $time = time() . rand(100, 999);
                    $filepath = md5($time) . "." . $extname;
                    $targetpath = $path . $filepath;
                    move_uploaded_file($tmp_name, $targetpath);
                    return $filepath;
                } else {
                    $newwidth = $imgWidth;
                    $newheight = ($height / $width) * $newwidth;
                    $tmp = imagecreatetruecolor($newwidth, $newheight);
                    imagecopyresampled($tmp, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
                    $time = time();
                    $filepath = md5($time) . "." . $extname;
                    $filename = $path . $filepath;
                    imagejpeg($tmp, $filename, 100);

                    imagedestroy($src);
                    imagedestroy($tmp);
                    return $filepath;
                }
            }
        }
    }

    function formatText($value) {
        $value = str_replace("“", "\"", $value);
        $value = str_replace("�?", "\"", $value);
        //$value = preg_replace('/[^(\x20-\x7F)\x0A]*/','', $value);
        $value = stripslashes($value);
        $value = html_entity_decode($value, ENT_QUOTES);
        $trans = get_html_translation_table(HTML_ENTITIES, ENT_QUOTES);
        $value = strtr($value, $trans);
        $value = stripslashes(trim($value));
        return $value;
    }

    function shortLength($value, $len) {
        $value_format = $this->formatText($value);
        $value_raw = html_entity_decode($value_format, ENT_QUOTES);

        if (strlen($value_raw) > $len) {
            $value_strip = substr($value_raw, 0, $len);
            $value_strip = $this->formatText($value_strip);
            $lengthvalue = "<span title='" . $value_format . "' rel='tooltip'>" . $value_strip . "...</span>";
        } else {
            $lengthvalue = $value_format;
        }
        return $lengthvalue;
    }

    function makeSeoUrl($url) {
        if ($url) {
            $url = trim($url);
            $value = preg_replace("![^a-z0-9]+!i", "-", $url);
            $value = trim($value, "-");
            return strtolower($value);
        }
    }

    function formatCustomField($url) {
        if ($url) {
            $url = trim($url);
            $value = preg_replace("![^a-z0-9]+!i", "_", $url);
            $value = trim($value, "_");
            return strtolower($value);
        }
    }

    function getExtension($str) {
        $i = strrpos($str, ".");
        if (!$i) {
            return "";
        }
        $l = strlen($str) - $i;
        $ext = substr($str, $i + 1, $l);
        return $ext;
    }

    function generate_password($length = 8) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $password = substr(str_shuffle($chars), 0, $length);
        return $password;
    }

    function generatePassword($length) {
        $vowels = 'aeuy';
        $consonants = '3@Z6!29G7#$QW4';
        $password = '';
        $alt = time() % 2;
        for ($i = 0; $i < $length; $i++) {
            if ($alt == 1) {
                $password .= $consonants[(rand() % strlen($consonants))];
                $alt = 0;
            } else {
                $password .= $vowels[(rand() % strlen($vowels))];
                $alt = 1;
            }
        }
        return $password;
    }

    function getRealIpAddress() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    function get_ip_address() {
        if (isSet($_SERVER)) {
            if (isSet($_SERVER["HTTP_X_FORWARDED_FOR"])) {
                $realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
            } elseif (isSet($_SERVER["HTTP_CLIENT_IP"])) {
                $realip = $_SERVER["HTTP_CLIENT_IP"];
            } else {
                $realip = $_SERVER["REMOTE_ADDR"];
            }
        } else {
            if (getenv('HTTP_X_FORWARDED_FOR')) {
                $realip = getenv('HTTP_X_FORWARDED_FOR');
            } elseif (getenv('HTTP_CLIENT_IP')) {
                $realip = getenv('HTTP_CLIENT_IP');
            } else {
                $realip = getenv('REMOTE_ADDR');
            }
        }

        return $realip;
    }

    function hashSSHA($password = NULL) {
        $salt = sha1(rand());
        $salt = substr($salt, 0, 10);
        $encrypted = base64_encode(sha1($password . $salt, true) . $salt);
        $hash = array("salt" => $salt, "encrypted" => $encrypted);
        return $hash;
    }

    public function checkhashSSHA($salt, $password) {
        $hash = base64_encode(sha1($password . $salt, true) . $salt);
        return $hash;
    }

    function emailText($value) {
        $value = stripslashes(trim($value));
        $value = str_replace('"', "\"", $value);
        $value = str_replace('"', "\"", $value);
        $value = preg_replace('/[^(\x20-\x7F)\x0A]*/', '', $value);
        return stripslashes($value);
    }

    function formatEmail($msg, $arrData) {
        foreach ($arrData as $key => $value) {
            if (strstr($msg, "[" . $key . "]")) {
                $msg = str_replace("[" . $key . "]", $value, $msg);
            }
        }
        if (strstr($msg, "[SITE_NAME]")) {
            $msg = str_replace('[SITE_NAME]', "<a href='" . HTTP_ROOT . "'>" . SITE_NAME . "</a>", $msg);
        }
        if (strstr($msg, "[SUPPORT_EMAIL]")) {
            $msg = str_replace('[SUPPORT_EMAIL]', "<a href='mailto:" . SUPPORT_EMAIL . "'>" . SUPPORT_EMAIL . "</a>", $msg);
        }
        return $msg;
    }

    function sendEmail($to, $from, $subject, $message, $bcc = '', $files = null, $header = 1, $footer = 1) {

        if (empty($to)) {
            return FALSE;
        }

        if ($header) {
            $hdr = '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
    <html>
    <head>
    <title>' . SITE_NAME . '</title>
    </head>
    <body>
    <table width="750" style="font-family:arial helvetica sans-serif" border="0" cellspacing="0" cellpadding="0">
    <tbody>       
        <tr> <td bgcolor="#3f51b5" style="padding: 5px 10px; text-align: center;"><img alt="" src="' . HTTP_ROOT . 'images/logo.png"/></td></tr>         
        <tr> <td style="border:1px solid #c6c6c6"><table width="100%" border="0" cellspacing="0" cellpadding="0"> <tbody>
            <tr>
                <td style="padding-left:12px;padding-right:12px;font-family:trebuchet ms,arial;font-size:13px">';
        }
        if ($footer) {
            $ftr = '</td>
            </tr>
            </tbody>
        </table></td>
        </tr>        
    </tbody>
    </table>
    </body>
    </html>';
        }

        $message = $hdr . $message . $ftr;
        $to = $this->emailText($to);
        $bcc = !empty($bcc) ? $this->emailText($bcc) : '';
        $subject = $this->emailText($subject);
        $message = $this->emailText($message);
        $message = str_replace("<script>", "&lt;script&gt;", $message);
        $message = str_replace("</script>", "&lt;/script&gt;", $message);
        $message = str_replace("<SCRIPT>", "&lt;script&gt;", $message);
        $message = str_replace("</SCRIPT>", "&lt;/script&gt;", $message);

        if (LIVE) {
            //Send Email by using Cakephp3.x
            $email = new Email('default');
            $email->setFrom([$from => SITE_NAME]);
            if (!empty($files)) {
                $email->attachments($files);
            }
            $email->setEmailFormat('html');
            $email->setTemplate(NULL);
            $email->setTo($to);
            if (!empty($bcc)) {
                $email->setBcc($bcc);
            }
            $email->setSubject($subject);
            $email->send($message);
        } else {
            if (!file_exists(WWW_ROOT . "tempemails/")) {
                mkdir(WWW_ROOT . "tempemails/", 0777, true);
            }
            $fileName = md5(uniqid()) . time() . ".html";
            $logFile = fopen(WWW_ROOT . "tempemails/" . $fileName, "w");
            $txt = "$message  \n";
            fwrite($logFile, $txt);
            fclose($logFile);
            return TRUE;
        }



        //Send Email by core php        
//        $from = SITE_NAME . "<" . $from . ">";
//        $bcc = "prakash.kumarguru@gmail.com";
//        $headers = 'MIME-Version: 1.0' . "\r\n";
//        $headers.= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
//        $headers.= 'From:' . $from . "\r\n";
//        $headers.= 'BCC:' . $bcc . "\r\n";
//        if (mail($to, $subject, $message, $headers)) {
//            return true;
//        } else {
//            return false;
//        }
    }

    function random_password($length = 8) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
        $password = substr(str_shuffle($chars), 0, $length);
        return $password;
    }

    public function uploadImagexx($tmpPath, $name, $path) {
        $allowedExt = ['jpg', 'png', 'jpeg'];
        $fileExt = strtolower(pathinfo($name, PATHINFO_EXTENSION));

        if (!in_array($fileExt, $allowedExt)) {
            return false;
        } else {

            if (file_exists($path . $name)) {
                $fileName = pathinfo($name, PATHINFO_FILENAME);
                $newName = $fileName . mt_rand() . '.' . $fileExt;
            } else {
                $newName = $name;
            }
            $newPath = $path . $newName;

            if (move_uploaded_file($tmpPath, $newPath)) {
                return $newName;
            } else {
                return false;
            }
        }
    }
    
    public function uploadImage($fileArr, $path = NULL) {
        try {
            $filename = strtolower($fileArr['name']);
            $tmpName = $fileArr['tmp_name'];
            $ext = $this->getExtension($filename);
            $allowedExt = ['jpg', 'jpeg', 'png'];
            
            if(!$path){
                $path = WWW_ROOT.WEBFRONT_LOGO;
            }
            if (!in_array($ext, $allowedExt)){
                return NULL;
            } else if (filesize($tmpName) > MAX_FILE_SIZE){
                return NULL;
            } else if (!is_dir($path)){
                mkdir($path);
            }
            
            if (!file_exists($path . $filename) && strlen($filename) < 150) {
                $newfilename = $filename;
            } else {
                $filenamesubstr = substr(pathinfo($filename, PATHINFO_FILENAME), 0, 100);
                $newfilename = $filenamesubstr . "-" . time() . rand(1000, 9999) . "." . $ext;
            }
                $targetPath = $path . $newfilename;
            if (move_uploaded_file($tmpName, $targetPath)) {
                return $newfilename;
            }
            
        } catch (\Exception $ex) {
            
        }
        return NULL;
    }

    public function photoName($tmpPath, $name, $path) {

        $photo = $this->uploadImage($tmpPath, $name, $path);

        if ($photo != false) {
            return $photo;
        } else {
            return '';
        }
    }

    function dateDisplay($datetime) {
        if ($datetime != "" && $datetime != "NULL" && $datetime != "0000-00-00 00:00:00") {
            return date("M d, Y", strtotime($datetime));
        } else {
            return false;
        }
    }
    
    public function shortUrlGenerator($longUrl) {
        try {
            
            // Using TinyURL
            // $shortUrl = file_get_contents("http://tinyurl.com/api-create.php?url=" . $longUrl);
            
            // Using BitLy
            $link = new Link;
            $link->setLongUrl($longUrl);
            $bitlyProvider = new BitlyProvider(
                new GenericAccessTokenAuthenticator('d78dcfd1748311099774b0e329fd925b905e4fa3')
            );

            $chainProvider = new ChainProvider;
            $chainProvider->addProvider($bitlyProvider);

            $chainProvider->getProvider('bitly')->shorten($link);

            $linkManager = new LinkManager($chainProvider);

            $expanded = $linkManager->findOneByProviderAndLongUrl('bitly', $longUrl);

            $shortUrl = $expanded->getShortUrl();
            
            return $shortUrl;
            
        } catch (\Exception $ex) {
          
        }    
        return $longUrl;  
    }

    public function qrCodeGenerator($url, $uniqueID) {
        try {
            $qrCode = new QrCode($url);
            $file = $uniqueID . '.png';
            $qrCode->writeFile(QR . $file);
            return $file;
        } catch (\Exception $ex) {
            
        }
        return '';
    }
    
    public function getUrlContent($url) {
        try {
            fopen("cookies.txt", "w");
            $parts = parse_url($url);
            $host = $parts['host'];
            $ch = curl_init();
            $header = array('GET /1575051 HTTP/1.1',
                "Host: {$host}",
                'Accept:text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                'Accept-Language:en-US,en;q=0.8',
                'Cache-Control:max-age=0',
                'Connection:keep-alive',
                'User-Agent:Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.116 Safari/537.36',
            );

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
            curl_setopt($ch, CURLOPT_COOKIESESSION, true);

            curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies.txt');
            curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies.txt');
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            $result = curl_exec($ch);
            curl_close($ch);
            return $result;
        } catch (\Exception $ex) {
            
        }
    }

}
