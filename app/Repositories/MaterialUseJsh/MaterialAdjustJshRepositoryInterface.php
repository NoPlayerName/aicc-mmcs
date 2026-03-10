<?php

namespace App\Repositories\MaterialUseJsh;

interface MaterialAdjustJshRepositoryInterface
{
    public function saveBulk(array $rows): bool;
    public function existsPlanDate(string $transactionDate): bool;
    public function getReport(?string $startDate, ?string $endDate, ?string $materialCode = null);
    public function getCombinedReport(?string $startDate, ?string $endDate, ?string $materialCode = null);
}
