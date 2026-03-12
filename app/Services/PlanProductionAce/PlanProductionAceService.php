<?php

namespace App\Services\PlanProductionAce;

use App\Models\Ace\MaterialUse\FurnaceHeadAce;
use App\Repositories\PlanProductionAce\PlanProdRepositoryAceInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PlanProductionAceService
{
    protected $planProdRepositoryAce;

    public function __construct(PlanProdRepositoryAceInterface $planProdRepositoryAce)
    {
        $this->planProdRepositoryAce = $planProdRepositoryAce;
    }

    // public function getPlanProd($date, $shift)
    // {
    //     return $this->planProdRepositoryAce->getPlanProd($date, $shift);
    // }

    public function generateFurnace()
    {
        $currentDateTime = now();
        $hour = $currentDateTime->hour;
        $shift = ($hour >= 7 && $hour < 20) ? 'D' : 'N';

        $productionDate = $currentDateTime->copy();
        if ($shift === 'N' && $hour < 7) {
            $productionDate->subDay();
        }

        $existingFurnaces = FurnaceHeadAce::query()
            ->whereDate('date', $productionDate->format('Y-m-d'))
            ->where('shift', $shift)
            ->whereBetween('furnace', [6, 9])
            ->pluck('furnace')
            ->map(fn($value) => (int) $value)
            ->toArray();

        if (count($existingFurnaces) >= 4) {
            return [
                'status' => false,
                'message' => 'Furnace 6-9 untuk tanggal dan shift ini sudah lengkap.',
            ];
        }

        $lastFurnace = FurnaceHeadAce::query()
            ->whereDate('date', $productionDate->format('Y-m-d'))
            ->where('shift', $shift)
            ->latest('id')
            ->value('furnace');

        $nextFurnace = ($lastFurnace >= 6 && $lastFurnace < 9) ? ((int) $lastFurnace + 1) : 6;

        $attempt = 0;
        while (in_array($nextFurnace, $existingFurnaces, true) && $attempt < 4) {
            $nextFurnace = $nextFurnace < 9 ? $nextFurnace + 1 : 6;
            $attempt++;
        }

        $data = [
            'furnace' => $nextFurnace,
            'shift' => $shift,
            'date' => $productionDate->format('Y-m-d'),
            'created_at' => now(),
            'created_by' => Auth::user()?->usr,
        ];

        $this->planProdRepositoryAce->generateFurnace($data);

        return [
            'status' => true,
            'message' => "Furnace {$nextFurnace} berhasil ditambahkan.",
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
        return $this->planProdRepositoryAce->getFurnaceHead(
            $dateValue,
            $shiftParam ?? $shift
        );
    }
    public function generateCharging($idFurnace)
    {
        $nextCharging = $this->planProdRepositoryAce->getNextChargingNumber($idFurnace);

        $data = [
            'plan_id_anchor' => $idFurnace,
            'charging' => $nextCharging,
            'created_by' => Auth::user()?->usr,
            'created_at' => now(),
        ];
        return $this->planProdRepositoryAce->generateCharging($data);
    }

    public function saveSelection($id, $productId, $lotIds)
    {
        $lotIds = implode(', ', $lotIds);
        return $this->planProdRepositoryAce->saveSelection($id, $productId, $lotIds);
    }
    public function getFurnace($date = null, $shiftParam = null)
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
        }

        $dateValue = ($parsedDate ?? $productionDate)->format('Y-m-d');

        return $this->planProdRepositoryAce->getFurnace(
            $dateValue,
            $shiftParam ?? $shift
        );
    }
}
