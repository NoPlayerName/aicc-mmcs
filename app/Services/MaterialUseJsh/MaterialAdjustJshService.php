<?php

namespace App\Services\MaterialUseJsh;

use App\Repositories\MaterialUseJsh\MaterialAdjustJshRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class MaterialAdjustJshService
{
    protected $repository;

    public function __construct(MaterialAdjustJshRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function saveAdjust(array $draftAdjust): array
    {
        if (empty($draftAdjust)) {
            return [
                'status' => false,
                'message' => 'Draft adjust masih kosong.',
            ];
        }

        $invalidDate = collect($draftAdjust)
            ->pluck('transaction_date')
            ->filter()
            ->unique()
            ->first(fn($date) => !$this->repository->existsPlanDate($date));

        if (!empty($invalidDate)) {
            return [
                'status' => false,
                'message' => "Tanggal transaksi {$invalidDate} tidak ditemukan pada plan production JSH.",
            ];
        }

        $user = Auth::user()?->usr;
        $now = now();

        $rows = collect($draftAdjust)->map(function ($item) use ($user, $now) {
            return [
                'transaction_date' => $item['transaction_date'],
                'materialable_id' => $item['material_id'],
                'materialable_type' => 'master',
                'qty_adjust' => $item['qty_adjust'],
                'note' => $item['note'] ?? null,
                'created_by' => $user,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        })->toArray();

        $saved = $this->repository->saveBulk($rows);

        if (!$saved) {
            return [
                'status' => false,
                'message' => 'Gagal menyimpan transaction adjust JSH.',
            ];
        }

        return [
            'status' => true,
            'message' => 'Transaction adjust JSH berhasil disimpan.',
        ];
    }

    public function getReport(?string $startDate, ?string $endDate, ?string $materialCode = null)
    {
        return $this->repository->getCombinedReport($startDate, $endDate, $materialCode);
    }

    public function getUsageReference(string $transactionDate, string $materialCode): float
    {
        $rows = $this->repository->getCombinedReport($transactionDate, $transactionDate, $materialCode);

        if (empty($rows)) {
            return 0;
        }

        return (float) ($rows[0]['usage_qty'] ?? 0);
    }
}
