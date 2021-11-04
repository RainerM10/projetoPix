<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Validation\Transaction as TransactionValidation;
use App\Repositories\Transaction as TransactionRepositories;

class TransactionController extends Controller {
    /**
     * @var TransactionValidation
     */
    protected $transactionValidation;

    /**
     * @var TransactionRepositories
     */
    protected $transactionRepositories;

    /**
     * Create a new controller instance.
     *
     * @param TransactionValidation $transactionValidation
     * @return void
     */
    public function __construct(TransactionValidation $transactionValidation, TransactionRepositories $transactionRepositories)
    {
        $this->transactionValidation = $transactionValidation;
        $this->transactionRepositories = $transactionRepositories;
    }

    //////////////////////////////////////////////////////////////////////////////////

    /**
     * This request will made the transfer.
     * 
     * @param Request $request
     * @return array
     */
    public function made(Request $request) {
        // Iremos validar os parâmetros que vieram da requisição.
        if ($this->transactionValidation->validateDate($request)) {
            // Confere se os usuários podem realizar a transferência.
            $arrayTransfer = $this->transactionRepositories->verifyData($request->all());
            if ($arrayTransfer['status'] == false) {
                $retorno['message'] = $arrayTransfer['message'];
                $retorno['status'] = false;
                $code = 500;
            } else {
                $arrayResponse = $this->transfer($arrayTransfer);
                if ($arrayResponse['status']) {
                    $retorno['status'] = true;
                    $retorno['message'] = $arrayResponse['message'];
                    $code = 200;
                } else {
                    $retorno['status'] = true;
                    $retorno['message'] = $arrayResponse['message'];
                    $code = 500;
                }
            }
        } else {
            $retorno['message'] = 'Algum(ns) do(s) parâmetros enviados não seguem o padrão exigido.';
            $retorno['status'] = false;
            $code = 400;  
        }
        return response()->json($retorno, $code);
    }

    //////////////////////////////////////////////////////////////////////////////////

    /**
     * This function made the action of transfer.
     * 
     * @param array $arrayTransfer
     * @return array
     */
    private function transfer($arrayTransfer) {
        return $this->transactionRepositories->transfer($arrayTransfer);
    }
}
