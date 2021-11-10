<?php

namespace App\Repositories;
use App\Account;
use App\Transaction;
use Exception;
use Illuminate\Support\Facades\DB;

class TransactionRepository extends BaseRepository {
    /**
     * Verify if the users can made the transfer.
     * 
     * @param array $arrayRequest;
     * 
     * @return array
     */
    public function verifyData($arrayRequest) {
        $arrayPayer = $this->collectData(0, $arrayRequest);
        $arrayPayee = $this->collectData(1, $arrayRequest);
        // Check if the payer exists.
        if ($arrayPayer != null && $arrayPayee != null) {
            // If so, the user cannot be a company.
            if ($arrayPayer[0]['role_id'] == 2) {
                return [
                    'status' => false,
                    'message' => 'Esse tipo de usuário não pode realizar pagamentos.'
                ];
            }
            // Checks whether the user who is making the payment has
            // in balance the money to make the payment.
            if ($arrayRequest['value'] > $arrayPayer[0]['balance']) {
                return [
                    'status' => false,
                    'message' => 'O usuário não possui o valor em saldo.'
                ];  
            }
            // If there is no error, we will return transfer data to the controller.
            return [
                'status' => true,
                'arrayPayer' => $arrayPayer[0],
                'arrayPayee' => $arrayPayee[0],
                'value' => $arrayRequest['value']
            ];
        } else {
            return [
                'status' => false,
                'message' => 'Um dos usuários participantes da transferência, não existe.'
            ]; 
        }
    }

    //////////////////////////////////////////////////////////////////////////////////

    /**
     * Collect data from request.
     * 
     * @param int $role
     * @param array $arrayRequest
     * 
     * @return object
     */
    private function collectData($role, $arrayRequest) {
        $account = new Account();
        // Payer User.
        if ($role == 0) {
            if (isset($arrayRequest['email_payer'])) {
                return $account->getUser($arrayRequest['email_payer']);
            } else {
                return $account->getUser($arrayRequest['cpf_payer']);
            }
        } else {
            if (isset($arrayRequest['email_receiver'])) {
                return $account->getUser($arrayRequest['email_receiver']);
            } else if (isset($arrayRequest['cpf_receiver'])) {
                return $account->getUser($arrayRequest['cpf_receiver']);
            } else {
                return $account->getUser($arrayRequest['cnpj_receiver']);
            }
        }
    }

    //////////////////////////////////////////////////////////////////////////////////

    /**
     * This function made the action of transfer.
     * 
     * @param array $arrayTransfer
     * 
     * @return array
     */
    public function transfer($arrayTransfer) {
        $transaction = new Transaction();
        $account = new Account();
        DB::beginTransaction();
        try {
            if($this->authorizationMock('https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6')) {
                // Record the transfer in transfer history.
                $id = $transaction->transfer($arrayTransfer);
                // Changes user balance amounts.
                $addValue = $account->addValue($arrayTransfer);
                $decrementValue = $account->decrementValue($arrayTransfer);
                if ($this->authorizationMock('http://o4d9z.mocklab.io/notify')) {
                    // If no error occurs during the update,
                    // the transfer is persisted in the database.
                    DB::commit();
                    return [
                        'status' => true,
                        'message' => 'A transferência ocorreu com sucesso.'
                    ];
                } else {
                    DB::rollback();
                    return [
                        'status' => false,
                        'message' => 'Ocorreu um erro do Mock de Comunicação.'
                    ]; 
                }
            } else {
                return [
                    'status' => false,
                    'message' => 'Ocorreu um erro do Mock de Autorização.'
                ];
            }
        } catch (Exception $e) {
            DB::rollback();
            return [
                'status' => false,
                'message' => 'Ocorreu um erro inesperado ao realizar a transferência.'
            ];
        }
    }

    //////////////////////////////////////////////////////////////////////////////////

    /**
     * This function made the request to authorization services.
     * 
     * @return boolean
     */
    private function authorizationMock() {
        try {
            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 15,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            ));
            $response = curl_exec($curl);
            $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);
            // Check if the answer was successful.
            if ($httpcode == 200) {
                $response = json_decode($response);
                if ($response->message == 'Autorizado') {
                    return true;
                }
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }
}