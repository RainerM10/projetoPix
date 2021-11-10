<?php

namespace App\Http\Requests;

use Mockery\Expectation;

class BaseRequest {
    /**
     * Base method for requests that use the GET protocol in the system.
     * 
     * @param string $url
     * 
     * @return array/boolean.
     */
    public function getRequest($url = null) {
        if ($url != null) {
            try {
                $curl = curl_init();
                curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 15,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                ));
                $response = curl_exec($curl);
                $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                curl_close($curl);
                return [
                    'httpCode' => $httpCode,
                    'response' => json_decode($response)
                ];
            } catch (Expectation $e) {
                return false;
            }
        }
        return false;
    }
}