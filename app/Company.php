<?php

namespace App;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    /////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * Insert a new consumer user.
     *  
	 * @param array $arrayUser
	 * @return array 
	 */
	public function insert($arrayInsert = null) {
        DB::beginTransaction();
        try {
            $arrayAcount = [
                'email' => $arrayInsert['email'],
                'document' => $arrayInsert['cnpj'],
                'role_id' => 2,
                'created_at' => date('Y-m-d H:i:s')
            ];
            $id = Account::insertGetId($arrayAcount);
            $arrayCompany = [
                'companyName' => $arrayInsert['companyName'],
                'password' => base64_encode(md5($arrayInsert['password'], true)),
                'account_id' => $id,
                'created_at' => date('Y-m-d H:i:s')
            ];
            $id = Company::insertGetId($arrayCompany);
            DB::commit();
            // Caso não ocorra nenhum erro durante a inserção,
            // é persistido no banco o usuário.
            return [
                'status' => true
            ];
        } catch(\Illuminate\Database\QueryException $ex){
            DB::rollback();
            // Erro que ocorre quando uma tabela que possui a coluna como UNIQUE
            // sofre a tentativa de se inserir um valor duplicado.
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
}