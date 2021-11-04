<?php

namespace App\Validation;

use Illuminate\Support\Facades\Validator;

class BaseValidation {
    public function baseValidation($request, $parameters) {
        $validated = Validator::make($request, $parameters);
        if (!$validated->fails()) {
            return true;
        }
        return false;
    }
}