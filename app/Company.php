<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model {
    protected $table = 'company';
    protected $fillable = [
        'id', 
        'fantasyName', 
        'cnpj', 
        'email', 
        'password',
        'created_at',
        'updated_at'
    ];
    public $timestamps = true;
}