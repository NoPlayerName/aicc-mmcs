<?php

namespace App\Services\PlanProductionAce;

use App\Models\Ace\MaterialUse\FurnaceHeadAce;
use App\Repositories\PlanProductionAce\PlanProdRepositoryAceInterface;
use Illuminate\Support\Facades\Auth;

class PlanProductionAceService
{
    protected $planProdRepositoryAce;

    public function __construct(PlanProdRepositoryAceInterface $planProdRepositoryAce)
    {
        $this->planProdRepositoryAce = $planProdRepositoryAce;
    }

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
            'created_at' => $currentDateTime,
            'created_by' => Auth::user()?->usr,
        ];

        $this->planProdRepositoryAce->generateFurnace($data);

        return [
            'status' => true,
            'message' => "Furnace {$nextFurnace} berhasil ditambahkan.",
        ];
    }
    public function getFurnaceHead()
    {
        $currentDateTime = now();
        $hour = $currentDateTime->hour;
        $shift = ($hour >= 7 && $hour < 20) ? 'D' : 'N';

        $productionDate = $currentDateTime->copy();
        if ($shift === 'N' && $hour < 7) {
            $productionDate->subDay();
        }

        return $this->planProdRepositoryAce->getFurnaceHead(
            $productionDate->format('Y-m-d'),
            $shift
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
}
