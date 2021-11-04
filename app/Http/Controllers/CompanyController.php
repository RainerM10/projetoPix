<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\Company;

class CompanyController extends Controller
{
    ////////////////////////////////////////////////////////////////
    ///////////////////////////// CRUD /////////////////////////////
    ////////////////////////////////////////////////////////////////

    /**
     * Create a new company.
     * 
     * @param Request $request
     * @return array
     */
    public function create(Request $request, Company $company) {
        // Iremos validar os parâmetros que vieram da requisição.
        if ($this->validateDate($request)) {
            $arrayUser = [
                'companyName' => $request->company_name,
                'cnpj' => $request->cnpj,
                'email' => $request->email,
                'password' => $request->password
            ];
            // Caso os parâmetros estejam de acordo com os requesitos,
            // envia os dados para ser persistido no banco.
            $insertUser = $company->insert($arrayUser);
            if ($insertUser['status']) {
                $retorno['message'] = 'O lojista foi cadastrado com sucesso.';
                $retorno['status'] = true;
                $code = 200;  
            } else {
                if ($insertUser['error']) {
                    $retorno['message'] = 'O e-mail ou CNPJ já foi cadastrado na conta de algum outro usuário.';
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

    ////////////////////////////////////////////////////////////////
    ////////////////////////// VALIDAÇÃO ///////////////////////////
    ////////////////////////////////////////////////////////////////

    /**
     * Validate the data.
     * 
     * @param Request $request
     * @return boolean
     */
    private function validateDate($request = null) {
        if ($request != null) {
            $validated = Validator::make($request->all(), [
                'company_name' => 'required|string',
                'cnpj' => 'required|integer|digits:14',
                'email' => 'required|string',
                'password' => 'required|string',
            ]);
            if (!$validated->fails()) {
                return true;
            }
        }
        return false;
    }
}
