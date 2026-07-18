<?php

namespace App\Repositories\PlanProductionJsh;

interface PlanProdRepositoryInterface
{
    public function generateData($date, $shift);
    public function generateFurnace($request);
    public function getFurnaceHead($date, $shift);
}
