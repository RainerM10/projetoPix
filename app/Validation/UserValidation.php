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

    ////////////////////////////////////////////////////////////////

    /**
     * Validate the CPF.
     * 
     * @param string $cpf
     * 
     * @return boolean
     */
    public function validateCpf($cpf = null) {
        if ($cpf != null) {
            if ($this->checkInvalidValues($cpf)) {
                // Validate size
                if (strlen($cpf) != 11) {
                    return false;
                }
                $cpf = str_split($cpf);
                // Calculate to validate the CPF.
                for ($t = 9; $t < 11; $t++) {
                    for ($d = 0, $c = 0; $c < $t; $c++) {
                        $d += $cpf[$c] * (($t + 1) - $c);
                    }
                    $d = ((10 * $d) % 11) % 10;
                    if ($cpf[$c] != $d) {
                        return false;
                    }
                }
                return true;
            }
        }
        return false;
    }

    ////////////////////////////////////////////////////////////////

    /**
     * Checks if the entered CPF is one of the values, not valid.
     * 
     * @param string $cpf
     * 
     * @return boolean
     */
    private function checkInvalidValues($cpf) {
        // Checks if the entered CNPJ is one of the values, not valid.
        $invalidArray = [
            '00000000000',
            '11111111111',
            '22222222222',
            '33333333333',
            '44444444444',
            '55555555555',
            '66666666666',
            '77777777777',
            '88888888888',
            '99999999999'
        ];
        if (in_array($cpf, $invalidArray)) {	
            return false;
        }
        return true;
    }
}