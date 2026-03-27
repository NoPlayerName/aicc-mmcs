<?php

namespace App\Repositories\MaterialUseAce;

interface LadleTfAdjustRepositoryInterface
{
    public function saveBulk(array $rows): bool;
    public function existsLadleDate(string $transactionDate): bool;
    public function getReport(?string $startDate, ?string $endDate, ?string $materialCode = null);
}
