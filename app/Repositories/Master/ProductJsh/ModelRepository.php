<?php

namespace App\Repositories\Master\ProductJsh;

use App\Models\Jsh\Molding\Models;

class ModelRepository implements ModelRepositoryInterface
{
    public function getModelJsh()
    {
        $query = Models::select('id', 'model', 'alias')->get();
        return $query;
    }
}
