<?php

namespace App;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Transaction extends Model {
    protected $table = 'transaction';
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
}