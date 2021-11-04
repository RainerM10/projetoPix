<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\User;
use App\Validation\User as UserValidation;
use App\Repositories\User as UserRepository;

class UserController extends Controller {
    /**
     * @var UserValidation
     */
    protected $userValidation;

    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * Create a new controller instance.
     *
     * @param UserValidation $userValidation
     * @param UserRepository $userRepository
     * @return void
     */
    public function __construct(UserValidation $userValidation, UserRepository $userRepository)
    {
        $this->userValidation = $userValidation;
        $this->userRepository = $userRepository;
    }

    ////////////////////////////////////////////////////////////////
    ///////////////////////////// CRUD /////////////////////////////
    ////////////////////////////////////////////////////////////////

    /**
     * Create a new consumer user.
     * 
     * @param Request $request
     * @return array
     */
    public function create(Request $request, User $user) {
        // Iremos validar os parâmetros que vieram da requisição.
        if ($this->userValidation->validateDate($request)) {
            $arrayInsert = [
                'fullName' => $request->full_name,
                'cpf' => $request->cpf,
                'email' => $request->email,
                'password' => $request->password
            ];
            // Caso os parâmetros estejam de acordo com os requesitos,
            // envia os dados para ser persistido no banco.
            $insertUser = $this->userRepository->create($user, $arrayInsert);
            if ($insertUser['status']) {
                $retorno['message'] = 'O usuário foi cadastrado com sucesso.';
                $retorno['status'] = true;
                $code = 200;  
            } else {
                if ($insertUser['error']) {
                    $retorno['message'] = 'O e-mail ou CPF já foi cadastrado na conta de algum outro usuário.';
                    $retorno['status'] = false;
                    $code = 403; 
                } else {
                    $retorno['message'] = 'Ocorreu um erro inesperado ao cadastrar o usuário.';
                    $retorno['status'] = false;
                    $code = 500; 
                }
            }
        } else {
            $retorno['message'] = 'Algum(ns) do(s) parâmetros enviados não seguem o padrão exigido.';
            $retorno['status'] = false;
            $code = 400;  
        }
        return response()->json($retorno, $code);
    }
}
