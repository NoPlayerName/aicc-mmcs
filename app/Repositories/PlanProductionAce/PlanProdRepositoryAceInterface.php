<?php

namespace App\Repositories\PlanProductionAce;

interface PlanProdRepositoryAceInterface
{
    public function generateFurnace($request);
    public function getFurnaceHead($date, $shift);
    public function getFurnace($date, $shift);
    public function generateCharging($data);
    public function getNextChargingNumber($data);
    public function saveSelection($id, $productId, $lotIds);
}
