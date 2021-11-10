<?php

namespace App\Validation;

class UserValidation extends BaseValidation {
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
                'full_name' => 'required|string',
                'cpf' => 'required|integer|digits:11',
                'email' => 'required|string|email',
                'password' => 'required|string'
            ]);
        }
        return false;
    }
}