<?php

namespace App;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Transaction extends Model {
    protected $table = 'transactions';
    protected $fillable = [
        'id', 
        'receiver_id', 
        'payer_id', 
        'value'
    ];

    /**
     * Performs the transfer in the database.
     * 
     * @param array $arrayTransfer
     * 
     * @return object
     */
    public function transfer($arrayTransfer) {
        return Transaction::insertGetId([
            'receiver_id' => $arrayTransfer['arrayPayee']['id'],
            'payer_id' => $arrayTransfer['arrayPayer']['id'],
            'value' => $arrayTransfer['value']
        ]);
    }
}