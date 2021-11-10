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
    public function validateData($request = null) {
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

    ////////////////////////////////////////////////////////////////

    /**
     * Validate the CNPJ.
     * 
     * @param string $cnpj
     * 
     * @return boolean
     */
    public function validateCnpj($cnpj = null) {
        if ($cnpj != null) {
            if ($this->checkInvalidValues($cnpj)) {
                // Validate size
                if (strlen($cnpj) != 14) {
                    return false;
                }
                $cnpj = str_split($cnpj);
                // Validate first check digit.
                $j = 5;
                $sum = 0;
                for ($i = 0; $i < 12; $i++) {
                    $sum += $cnpj[$i] * $j;
                    $j = ($j == 2) ? 9 : $j - 1;
                }
                $rest = $sum % 11;
                if ($cnpj[12] != ($rest < 2 ? 0 : 11 - $rest)) {
                    return false;
                }
                // Validate second check digit.
                $j = 6;
                $sum = 0;
                for ($i = 0; $i < 13; $i++)
                {
                    $sum += $cnpj[$i] * $j;
                    $j = ($j == 2) ? 9 : $j - 1;
                }
                $rest = $sum % 11;
                return $cnpj[13] == ($rest < 2 ? 0 : 11 - $rest);
            }
        }
        return false;
    }

    ////////////////////////////////////////////////////////////////

    /**
     * Checks if the entered CNPJ is one of the values, not valid.
     * 
     * @param string $cnpj
     * 
     * @return boolean
     */
    private function checkInvalidValues($cnpj) {
        // Checks if the entered CNPJ is one of the values, not valid.
        $invalidArray = [
            '00000000000000',
            '11111111111111',
            '22222222222222',
            '33333333333333',
            '44444444444444',
            '55555555555555',
            '66666666666666',
            '77777777777777',
            '88888888888888',
            '99999999999999'
        ];
        if (in_array($cnpj, $invalidArray)) {	
            return false;
        }
        return true;
    }
}