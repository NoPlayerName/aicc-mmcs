<?php

namespace App\Services\PlanProductionJsh;

use App\Models\Jsh\MaterialUse\FurnaceHead;
use App\Repositories\MaterialUseJsh\MaterialUseJshRepositoryInterface;
use App\Repositories\PlanProductionJsh\PlanProdRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

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

    //manual genereate furnace
    public function generateFurnace()
    {
        $currentDateTime = now();
        $hour = $currentDateTime->hour;
        $shift = ($hour >= 7 && $hour < 20) ? 'D' : 'N';

        $productionDate = $currentDateTime->copy();
        if ($shift === 'N' && $hour < 7) {
            $productionDate->subDay();
        }

        $existingFurnaces = FurnaceHead::query()
            ->whereDate('date', $productionDate->format('Y-m-d'))
            ->where('shift', $shift)
            ->whereBetween('furnace', [1, 5])
            ->pluck('furnace')
            ->map(fn($value) => (int) $value)
            ->toArray();

        if (count($existingFurnaces) >= 5) {
            return [
                'status' => false,
                'message' => 'Furnace 1-5 untuk tanggal dan shift ini sudah lengkap.',
            ];
        }

        $lastFurnace = FurnaceHead::query()
            ->whereDate('date', $productionDate->format('Y-m-d'))
            ->where('shift', $shift)
            ->latest('id')
            ->value('furnace');

        $nextFurnace = ($lastFurnace >= 1 && $lastFurnace < 5) ? ((int) $lastFurnace + 1) : 1;

        $attempt = 0;
        while (in_array($nextFurnace, $existingFurnaces, true) && $attempt < 5) {
            $nextFurnace = $nextFurnace < 5 ? $nextFurnace + 1 : 1;
            $attempt++;
        }

        $data = [
            'furnace' => $nextFurnace,
            'shift' => $shift,
            'date' => $productionDate->format('Y-m-d'),
            'created_at' => now(),
            'created_by' => Auth::user()?->usr,
        ];

        $status =  $this->PlanProduction->generateFurnace($data);

        return [
            'status' => $status['status'],
            'message' => $status['message'],
            'data' => $status['data'],
        ];
    }

    public function getFurnaceHead($date = null, $shiftParam = null)
    {
        $currentDateTime = now();
        $hour = $currentDateTime->hour;
        $shift = ($hour >= 7 && $hour < 20) ? 'D' : 'N';

        $productionDate = $currentDateTime->copy();
        if ($shift === 'N' && $hour < 7) {
            $productionDate->subDay();
        }
        if (!empty($date)) {
            $parsedDate = Carbon::createFromFormat('d/m/Y', $date);
            // dd($date, $productionDate, $shiftParam ?? $shift);
        }

        $dateValue = ($parsedDate ?? $productionDate)->format('Y-m-d');
        // dd($date, $productionDate, $shiftParam ?? $shift);
        return $this->PlanProduction->getFurnaceHead(
            $dateValue,
            $shiftParam ?? $shift
        );
    }
}
