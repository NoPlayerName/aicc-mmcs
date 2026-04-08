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

    // private function furnaceJsh($tanggal, $shift)
    // {
    //     return $this->MaterialUseJsh->getFurnace($tanggal, $shift);
    // }

    public function getPlanProd($date, $shift)
    {
        $currentDateTime = now();
        $hour = $currentDateTime->hour;
        $shiftByHour = ($hour >= 7 && $hour < 20) ? 'D' : 'N';
        $productionDate = $currentDateTime->copy();

        if ($shiftByHour === 'N' && $hour < 7) {
            $productionDate->subDay();
        }

        if (empty($date) || empty($shift)) {
            $tanggal = $productionDate->format('Y-m-d');
            $shiftNow = $shiftByHour;
        } else {
            $tanggal = Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d');
            $shiftNow = $shift;
        }

        return $this->PlanProduction->generateData($tanggal, $shiftNow);
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
