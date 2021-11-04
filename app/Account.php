<?php

namespace App;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Account extends Model {
    protected $table = 'account';
    protected $fillable = [
        'id', 
        'email', 
        'document', 
        'balance',
        'role_id',
        'created_at',
        'updated_at'
    ];
    public $timestamps = true;

    public function getUser($data) {
        return Account::where(['email' => $data])
            ->orWhere(['document' => $data])
            ->get()->toArray();
    }

    public function addValue($arrayTransfer) {
        return Account::find($arrayTransfer['arrayPayee']['id'])->increment('balance', $arrayTransfer['value']);
    }

    public function decrementValue($arrayTransfer) {
        return Account::find($arrayTransfer['arrayPayer']['id'])->decrement('balance', $arrayTransfer['value']);
    }
}