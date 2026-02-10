<?php

namespace  App\Services\Master\ProductJsh;

use App\Repositories\Master\ProductJsh\ModelRepositoryInterface;

class  ModelService
{
    protected $model;

    public function __construct(ModelRepositoryInterface $model)
    {
        $this->model = $model;
    }

    public function getModel()
    {
        $data = $this->model->getModelJsh();
        return $data;
    }
}
