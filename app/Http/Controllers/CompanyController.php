<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Company;
use App\Validation\Company as CompanyValidation;
use App\Repositories\Company as CompanyRepository;

class CompanyController extends Controller
{
    /**
     * @var CompanyValidation
     */
    protected $companyValidation;

    /**
     * @var CompanyRepository
     */
    protected $companyRepository;

    /**
     * Create a new controller instance.
     *
     * @param CompanyValidation $userValidation
     * @return void
     */
    public function __construct(CompanyValidation $companyValidation, CompanyRepository $companyRepository)
    {
        $this->companyValidation = $companyValidation;
        $this->companyRepository = $companyRepository;
    }

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
        if ($this->companyValidation->validateDate($request)) {
            $arrayInsert = [
                'companyName' => $request->company_name,
                'cnpj' => $request->cnpj,
                'email' => $request->email,
                'password' => $request->password
            ];
            // Caso os parâmetros estejam de acordo com os requesitos,
            // envia os dados para ser persistido no banco.
            $insertUser = $this->companyRepository->create($company, $arrayInsert);
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
}
