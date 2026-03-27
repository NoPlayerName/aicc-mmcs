<?php

namespace App\Services\MaterialUseAce;

use App\Repositories\MaterialUseAce\LadleTfAdjustRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class LadleTfAdjustService
{
    protected $repository;

    public function __construct(LadleTfAdjustRepositoryInterface $repository)
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
            ->first(fn($date) => !$this->repository->existsLadleDate($date));

        if (!empty($invalidDate)) {
            return [
                'status' => false,
                'message' => "Tanggal transaksi {$invalidDate} tidak ditemukan pada data Ladle Transfer ACE.",
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
                'message' => 'Gagal menyimpan transaction adjust Ladle Transfer ACE.',
            ];
        }

        return [
            'status' => true,
            'message' => 'Transaction adjust Ladle Transfer ACE berhasil disimpan.',
        ];
    }

    public function getReport(?string $startDate, ?string $endDate, ?string $materialCode = null)
    {
        return $this->repository->getReport($startDate, $endDate, $materialCode);
    }
}
