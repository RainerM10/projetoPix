<?php

namespace App\Repositories;
use App\Account;
use App\Transaction as TransactionModel;
use Exception;
use Illuminate\Support\Facades\DB;

class Transaction extends BaseRepository {
    /**
     * Verify if the users can made the transfer.
     * 
     * @param array $arrayRequest;
     * 
     * @return boolean
     */
    public function verifyData($arrayRequest) {
        $arrayPayer = $this->collectData(0, $arrayRequest);
        $arrayPayee = $this->collectData(1, $arrayRequest);
        // Confere se o pagador existe.
        if ($arrayPayer != null && $arrayPayee != null) {
            // Caso exista, o usuário não pode ser do tipo lojista.
            if ($arrayPayer[0]['role_id'] == 2) {
                return [
                    'status' => false,
                    'message' => 'Esse tipo de usuário não pode realizar pagamentos.'
                ];
            }
            // Confere se o usuário que está realizando o pagamento, possui
            // em saldo o dinheiro para realizar o pagamento.
            if ($arrayRequest['value'] > $arrayPayer[0]['balance']) {
                return [
                    'status' => false,
                    'message' => 'O usuário não possui o valor em saldo.'
                ];  
            }
            // Caso não haja erro, retornaremos os dados dos usuários para o controller.
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
     * @param 
     */
    private function collectData($role, $arrayRequest) {
        $account = new Account();
        // Usuário pagador.
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
     * @return array
     */
    public function transfer($arrayTransfer) {
        $transaction = new TransactionModel();
        $account = new Account();
        DB::beginTransaction();
        try {
            if($this->authorizationMock('https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6')) {
                // Registra a transferência no histórico de transferência.
                $id = $transaction->transfer($arrayTransfer);
                // Muda os valores do saldo dos usuários.
                $addValue = $account->addValue($arrayTransfer);
                $decrementValue = $account->decrementValue($arrayTransfer);
                if ($this->authorizationMock('http://o4d9z.mocklab.io/notify')) {
                    // Caso não ocorra nenhum erro durante o update,
                    // é persistido no a transferência.
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
     * @param array $arrayTransfer
     * @return array
     */
    private function authorizationMock($url) {
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
            // Confere se houve sucesso na resposta.
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