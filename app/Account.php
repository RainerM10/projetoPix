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

    /**
     * Collects the user, via email or document.
     * 
     * @param string @data
     * 
     * @return object
     */
    public function getUser($data) {
        return Account::where(['email' => $data])
            ->orWhere(['document' => $data])
            ->get()->toArray();
    }

    //////////////////////////////////////////////////////////////////////////////////

    /**
     * Add the amount that will be added for the user who is receiving payment.
     * 
     * @param array $arrayTransfer
     * 
     * @return object
     */
    public function addValue($arrayTransfer) {
        return Account::find($arrayTransfer['arrayPayee']['id'])->increment('balance', $arrayTransfer['value']);
    }

    //////////////////////////////////////////////////////////////////////////////////

    /**
     * Subtracts the amount of the user making the payment.
     * 
     * @param array $arrayTransfer
     * 
     * @return object
     */
    public function decrementValue($arrayTransfer) {
        return Account::find($arrayTransfer['arrayPayer']['id'])->decrement('balance', $arrayTransfer['value']);
    }
}