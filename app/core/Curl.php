<?php

 /**
  * Curl class
  *
  * Used to handle CURL request
  *
  
  * @author     Mark Solly <mark@baledout.com.au>
  */

class Curl{

    /**
     * Constructor
     *
     */
    private function __construct(){}


    public static function sendPostRequest($url,  array $data, $method = '')
    {
        $ch = curl_init();

        if($method == 'form')
        {
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Content-Type: application/x-www-form-urlencoded",
                "cache-control: no-cache"
                )
            );
            $fields_string = '';
            foreach($data as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
            rtrim($fields_string, '&');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        }
        else //json by default
        {
            $data_string = json_encode($data);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Cache-Control: no-cache',
                'Content-Length: ' . strlen($data_string)
                )
            );
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_ENCODING, "");
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);
        if ($err)
        {
            Logger::log("CURL Error", $err." ".$url." ".print_r($data, true), __FILE__, __LINE__);
            return false;
        }
        else
        {
            return $result;
        }
    }
 }