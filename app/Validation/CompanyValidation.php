<?php

namespace App\Validation;

class CompanyValidation extends BaseValidation {
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
                'company_name' => 'required|string',
                'cnpj' => 'required|integer|digits:14',
                'email' => 'required|string|email',
                'password' => 'required|string'
            ]);
        }
        return false;
    }
}