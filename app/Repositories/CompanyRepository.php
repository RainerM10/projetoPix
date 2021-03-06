<?php

namespace App\Repositories;

class CompanyRepository extends BaseRepository {
    /**
     * Create a new Company.
     * 
     * @param Model $model
     * @param array $arrayInsert
     * 
     * @return boolean/array
     */
    public function create($model = null, $arrayInsert = null) {
        if ($model != null && $arrayInsert != null) {
            $arrayInsert['document'] = $arrayInsert['cnpj'];
            $arrayInsert['role_id'] = 2;
            return $this->insert($model, $arrayInsert);
        }
        return false;
    }
}