<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Validation\TransactionValidation;
use App\Repositories\TransactionRepository;

class TransactionController extends Controller {
    /**
     * @var TransactionValidation
     */
    protected $transactionValidation;

    /**
     * @var TransactionRepository
     */
    protected $transactionRepository;

    /**
     * Create a new controller instance.
     *
     * @param TransactionValidation $transactionValidation
     * @param TransactionRepository $transactionRepository
     * 
     * @return void
     */
    public function __construct(TransactionValidation $transactionValidation, TransactionRepository $transactionRepository) {
        $this->transactionValidation = $transactionValidation;
        $this->transactionRepository = $transactionRepository;
    }

    //////////////////////////////////////////////////////////////////////////////////

    /**
     * This request will made the transfer.
     * 
     * @param Request $request
     * 
     * @return ResponseFactory
     */
    public function made(Request $request) {
        // We will validate the request parameters.
        if ($this->transactionValidation->validateDate($request)) {
            // Checks if users can made the transfer.
            $arrayTransfer = $this->transactionRepository->verifyData($request->all());
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
     * 
     * @return array
     */
    private function transfer($arrayTransfer) {
        return $this->transactionRepository->transfer($arrayTransfer);
    }
}
