<?php

namespace App\Services\PlanProductionJsh;

use App\Models\Jsh\MaterialUse\FurnaceHead;
use App\Repositories\MaterialUseJsh\MaterialUseJshRepositoryInterface;
use App\Repositories\PlanProductionJsh\PlanProdRepositoryInterface;
use Carbon\Carbon;

class PlanProductionService
{
    protected $PlanProduction;
    protected $MaterialUseJsh;
    public function __construct(PlanProdRepositoryInterface $PlanProduction, MaterialUseJshRepositoryInterface $MaterialUseJsh)
    {
        $this->PlanProduction = $PlanProduction;
        $this->MaterialUseJsh = $MaterialUseJsh;
    }

    private function furnaceJsh($tanggal, $shift)
    {
        return $this->MaterialUseJsh->getFurnace($tanggal, $shift);
    }

    public function getPlanProd($date, $shift)
    {
        $tanggal = Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d');
        // dd($tanggal);
        // dd($this->PlanProduction->generateData($tanggal, $shift));
        return $this->PlanProduction->generateData($tanggal, $shift);
        // $data = FurnaceHead::where('date', $tanggal)
        //     ->where('shift', $shift)
        //     ->exists();
        // if ($data) {
        //     $data = $this->furnaceJsh($tanggal, $shift);
        //     return $data != null ? $data : null;
        // } else {
        //     $generate =  $this->PlanProduction->generateData($tanggal, $shift);
        //     return $generate ? $this->furnaceJsh($tanggal, $shift) : null;
        // }
    }
}
