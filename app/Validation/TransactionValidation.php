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
    public function validateDate($request = null) {
        if ($request != null) {
            return $this->baseValidation($request->all(), [
                'email_payer' => 'required_without:cpf_payer|string|email|different:email_receiver',
                'cpf_payer' => 'required_without:email_payer|email|integer|digits:11|different:cpf_receiver',
                'email_receiver' => 'required_without_all:cpf_receiver,cnpj_receiver|string|email|different:email_payer',
                'cpf_receiver' => 'required_without_all:email_receiver,cnpj_receiver|email|integer|digits:11|different:cpf_payer',
                'cnpj_receiver' => 'required_without_all:email_receiver,cpf_receiver|email|integer|digits:14',
                'value' => 'required|regex:/^\d+(\.\d{1,2})?$/'
            ]);
        }
        return false;
    }
}