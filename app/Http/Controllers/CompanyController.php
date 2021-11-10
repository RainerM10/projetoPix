<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Company;
use App\Validation\CompanyValidation;
use App\Repositories\CompanyRepository;

class CompanyController extends Controller {
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
     * @param CompanyValidation $companyValidation
     * @param CompanyRepository $companyRepository
     * 
     * @return void
     */
    public function __construct(CompanyValidation $companyValidation, CompanyRepository $companyRepository) {
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
     * @param Company $company
     * 
     * @return ResponseFactory
     */
    public function create(Request $request, Company $company) {
        // We will validate the request parameters.
        if ($this->companyValidation->validateDate($request)) {
            $arrayInsert = [
                'companyName' => $request->company_name,
                'cnpj' => $request->cnpj,
                'email' => $request->email,
                'password' => $request->password
            ];
            // We will validate the CNPJ.
            if ($this->companyValidation->validateCnpj($request->cnpj)) {
                // If the parameters meet the requirements,
                // send the data to be persisted in the database.
                $insertUser = $this->companyRepository->create($company, $arrayInsert);
                if ($insertUser['status']) {
                    $retorno['message'] = 'O lojista foi cadastrado com sucesso.';
                    $retorno['status'] = true;
                    $code = 201;  
                } else {
                    if ($insertUser['error']) {
                        $retorno['message'] = 'O e-mail ou CNPJ já foi cadastrado na conta de algum outro usuário.';
                        $retorno['status'] = false;
                        $code = 409; 
                    } else {
                        $retorno['message'] = 'Ocorreu um erro inesperado ao cadastrar o usuário.';
                        $retorno['status'] = false;
                        $code = 500; 
                    }
                }
            } else {
                $retorno['message'] = 'O CNPJ não é válido.';
                $retorno['status'] = false;
                $code = 400;      
            }
        } else {
            $retorno['message'] = 'Algum(ns) do(s) parâmetros enviados não seguem o padrão exigido.';
            $retorno['status'] = false;
            $code = 400;  
        }
        return response()->json($retorno, $code);
    }
}
