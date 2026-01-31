<?php

namespace App\Repositories\PlanProductionJsh;

interface PlanProdRepositoryInterface
{
    public function generateData($date, $shift);
}
