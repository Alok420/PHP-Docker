<?php

interface smsDeclare {

    function sendSms($message, $number, $username = 'Leelahotel', $password = '471a9ecb', $url = 'http://makemysms.in/api/sendsms.php');
}

class SMS implements smsDeclare {

    function sendSms($message, $number, $username = 'Leelahotel', $password = '471a9ecb', $url = 'http://makemysms.in/api/sendsms.php') {
        $senderid = 'LEELAH';
        $type = '1';
        $product = '1';
        $credentials = 'username=' . $username . '&password=' . $password;
        $data = '&sender=' . $senderid . '&mobile=' . $number . '&type=' . $type . '&product=' . $product . '&message=' . $message;
        $ch = curl_init();
        $curlConfig = array(
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => array(
                'username' => $username,
                'password' => $password,
                'sender' => $senderid,
                'mobile' => $number,
                'type' => $type,
                'product' => $product,
                'message' => $message,
            )
        );
        curl_setopt_array($ch, $curlConfig);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

}
