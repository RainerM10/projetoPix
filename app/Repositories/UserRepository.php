<?php

namespace App\Repositories;

class UserRepository extends BaseRepository {
    /**
     * Create a new User.
     * 
     * @param Model $model
     * @param array $arrayInsert
     * 
     * @return boolean/array
     */
    public function create($model = null, $arrayInsert = null) {
        if ($model != null && $arrayInsert != null) {
            $arrayInsert['document'] = $arrayInsert['cpf'];
            $arrayInsert['role_id'] = 1;
            return $this->insert($model, $arrayInsert);
        }
        return false;
    }
}