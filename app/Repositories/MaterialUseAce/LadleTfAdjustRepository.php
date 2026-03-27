<?php

namespace App\Repositories\MaterialUseAce;

use App\Models\Ace\MaterialUse\LadleTfAdjust;
use App\Models\Ace\MaterialUse\LadleTfHead;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LadleTfAdjustRepository implements LadleTfAdjustRepositoryInterface
{
    public function saveBulk(array $rows): bool
    {
        DB::beginTransaction();
        try {
            LadleTfAdjust::insert($rows);
            DB::commit();
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Save ladle tf adjust fail', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ]);
            return false;
        }
    }

    public function existsLadleDate(string $transactionDate): bool
    {
        return LadleTfHead::whereHas('furnace', function ($q) use ($transactionDate) {
            $q->whereDate('date', $transactionDate);
        })->exists();
    }

    public function getReport(?string $startDate, ?string $endDate, ?string $materialCode = null)
    {
        $query = LadleTfAdjust::with('materialable')->orderBy('transaction_date');

        if (!empty($startDate) && !empty($endDate)) {
            $query->whereBetween('transaction_date', [$startDate, $endDate]);
        }

        if (!empty($materialCode)) {
            $query->where('materialable_id', $materialCode);
        }

        return $query->get()->map(function ($item) {
            return [
                'transaction_date' => optional($item->transaction_date)->format('Y-m-d'),
                'material_id' => $item->materialable_id,
                'material_name' => $item->materialable?->material_name ?? '-',
                'qty_adjust' => (float) $item->qty_adjust,
                'note' => $item->note,
                'created_by' => $item->created_by,
                'created_at' => optional($item->created_at)->format('Y-m-d H:i:s'),
            ];
        })->toArray();
    }
}
