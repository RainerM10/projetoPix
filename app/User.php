<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model {
    protected $table = 'user';
    protected $fillable = [
        'id', 
        'fullName', 
        'password',
        'account_id',
        'created_at',
        'updated_at'
    ];
    public $timestamps = true;

    /**
     * Collects the user, via account_id.
     * 
     * @param string @data
     * 
     * @return object
     */
    public function getUserByAccountId($data) {
        return User::where(['account_id' => $data])
            ->get()->toArray();
    }
}