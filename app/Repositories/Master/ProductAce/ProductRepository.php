<?php

namespace App\Repositories\Master\ProductAce;

use App\Models\Ace\KanbanNew\ProductKanban;

class ProductRepository implements ProductRepositoryInterface
{
    public function getAllProducts()
    {
        return ProductKanban::all();
    }
}
