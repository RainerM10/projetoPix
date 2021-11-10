<?php

namespace App\Repositories;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Account;

class BaseRepository {
    /**
     * Insert a new Account and User in Database.
     * 
     * @param Model $model that will be insert, can be User or Company.
     * @param array $arrayInsert, containt the data that will be insert.
     * 
     * @return array
     */
    public function insert($model, $arrayInsert) {
        DB::beginTransaction();
        try {
            $id = $this->insertAccount($arrayInsert);
            $this->insertUser($model, $arrayInsert, $id);
            // If no error occurs during insertion,
            // the user is persisted in the database.
            DB::commit();
            return [
                'status' => true
            ];
        } catch(\Illuminate\Database\QueryException $ex){
            DB::rollback();
            // Error that occurs when a table that has the column as UNIQUE
            // suffers an attempt to insert a duplicate value.
            if ($ex->getCode()  == 23000) {
                return [
                    'status' => false,
                    'error' => 1
                ];
            }
        }
        return [
            'status' => false,
            'error' => 0
        ];
    }

    //////////////////////////////////////////////////////////////////////////////////

    /**
     * Insert in Account Table the new user.
     * 
     * @param $arrayInsert
     * 
     * @return object
     */
    private function insertAccount($arrayInsert) {
        $arrayAcount = [
            'email' => $arrayInsert['email'],
            'document' => $arrayInsert['document'],
            'role_id' => $arrayInsert['role_id'],
            'created_at' => date('Y-m-d H:i:s')
        ];
        return Account::insertGetId($arrayAcount);
    }

    //////////////////////////////////////////////////////////////////////////////////

    /**
     * Insert in User/Company the new user.
     * 
     * @param Model $model
     * @param array $arrayInsert
     * @param int $id
     * 
     * @return object
     * 
     */
    private function insertUser($model, $arrayInsert, $id) {
        $arrayModel = [
            'password' => base64_encode(md5($arrayInsert['password'], true)),
            'account_id' => $id,
            'created_at' => date('Y-m-d H:i:s')
        ];
        // It checks whether the field with the name will be a user or a company, according to
        // the role_id.
        if ($arrayInsert['role_id'] == 1) {
            $arrayModel['fullName'] = $arrayInsert['fullName'];
        } else {
            $arrayModel['companyName'] = $arrayInsert['companyName'];
        }
        return $model::insertGetId($arrayModel);
    }
}