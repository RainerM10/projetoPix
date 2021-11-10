<?php

namespace App\Http\Requests;

use Exception;

class TransactionRequest extends BaseRequest {
    /**
     * Method that will perform the Authorization Mock Request.
     * 
     * @return boolean
     */
    public function authorizationMock() {
        $request = $this->getRequest('https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6');
        if ($request != false) {
            try {
                if ($request['httpCode'] == 200 && $request['response']->message == 'Autorizado') {
                    return true;
                }
            } catch (Exception $e) {
                return false;
            }
        }
        return false;
    }

    //////////////////////////////////////////////////////////////////////////////////

    /**
     * Method that will perform the Communication Mock Request.
     * 
     * @return boolean
     */
    public function communicationMock() {
        $request = $this->getRequest('http://o4d9z.mocklab.io/notify');
        if ($request != false) {
            try {
                if ($request['httpCode'] == 200 && $request['response']->message == 'Success') {
                    return true;
                }
            } catch (Exception $e) {
                return false;
            }
        }
        return false;  
    }
}