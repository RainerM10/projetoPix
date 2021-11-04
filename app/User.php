<?php

namespace App;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Account;

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
                'document' => $arrayInsert['cpf'],
                'role_id' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ];
            $id = Account::insertGetId($arrayAcount);
            $arrayUser = [
                'fullName' => $arrayInsert['fullName'],
                'password' => base64_encode(md5($arrayInsert['password'], true)),
                'account_id' => $id,
                'created_at' => date('Y-m-d H:i:s')
            ];
            User::insertGetId($arrayUser);
            // Caso não ocorra nenhum erro durante a inserção,
            // é persistido no banco o usuário.
            DB::commit();
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
                    'error' => true
                ];
            }
        }
        return [
            'status' => false,
            'error' => false
        ];
    }
}