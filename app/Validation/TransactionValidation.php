<?php

namespace App\Validation;

class TransactionValidation extends BaseValidation {
    /**
     * Validate the data.
     * 
     * @param Request $request
     * 
     * @return boolean
     */
    public function validateData($request = null) {
        if ($request != null) {
            return $this->baseValidation($request->all(), [
                'email_payer' => 'required_without:cpf_payer|string|email',
                'cpf_payer' => 'required_without:email_payer|integer|digits:11',
                'email_receiver' => 'required_without_all:cpf_receiver,cnpj_receiver|string|email',
                'cpf_receiver' => 'required_without_all:email_receiver,cnpj_receiver|integer|digits:11',
                'cnpj_receiver' => 'required_without_all:email_receiver,cpf_receiver|integer|digits:14',
                'password' => 'required|string',
                'value' => 'required|regex:/^\d+(\.\d{1,2})?$/'
            ]);
        }
        return false;
    }

    //////////////////////////////////////////////////////////////////////////////////

    /**
     * Validation that the data are not the same, for payer and receiver.
     * 
     * @param Request $request
     * 
     * @return boolean
     */
    public function validateCommonData($request = null) {
        return $this->baseValidation($request->all(), [
            'email_payer' => 'different:email_receiver',
            'cpf_payer' => 'different:cpf_receiver',
            'email_receiver' => 'different:email_payer',
            'cpf_receiver' => 'different:cpf_payer',
        ]);
    }
}