<?php

function sendSMS($mobiles, $message) {
    try {
        $authkey = '249677AO47Tc2u5P8i5c00cb78';
        $message = urlencode($message);
        //?encrypt=&flash=&unicode=&schtime=&afterminutes=&response=&campaign=";
        $url = "http://api.msg91.com/api/sendhttp.php?country=91&sender=MSGIND&route=4&mobiles={$mobiles}&authkey={$authkey}&message={$message}";

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if (empty($err)) {
            return TRUE;
        }
    } catch (\Exception $ex) {
        
    }
    return FALSE;
}

function sanitize($input) {
    if (is_array($input)) {
        foreach ($input as $key => $value) {
            if (is_array($input)) {
                $input[$key] = sanitize($value);
            } else {
                $input[$key] = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $value);
            }
        }
    } else {
        $input = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $input);
    }
    return $input;
}

function formatPrice($price, $decimal = 2) {
    return number_format((float) $price, $decimal, '.', '') + 0;
}

 function generateUniqId($id = NULL) {
    $uniq = uniqid(rand()) . uniqid(rand());
    if ($id) {
        return md5($uniq . time() . $id);
    } else {
        return md5($uniq . time());
    }
}

function getFirstError($errors) {
    $errMsg = "Error Occured!!";
    try {
        $errors = array_shift($errors);
        $errMsg = array_shift($errors);
    } catch (\Exception $ex) {
        
    }
    return $errMsg;
}

function sendResponse($data) {
    echo json_encode($data);
    exit;
}

function getTxnID($id = NULL) {
    $newTransactionID = "PT" . time() . rand(1111, 9999);
    return $newTransactionID;
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

function formatInvoiceNo($id) {    
    return sprintf('%04d', $id);
}