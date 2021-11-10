<?php

namespace App\Validation;

use Illuminate\Support\Facades\Validator;

class BaseValidation {
    /**
     * Base validation method
     * 
     * @param Request $request
     * @param array $parameters
     * 
     * @return boolean
     */
    public function baseValidation($request, $parameters) {
        $validated = Validator::make($request, $parameters);
        if (!$validated->fails()) {
            return true;
        }
        return false;
    }
}