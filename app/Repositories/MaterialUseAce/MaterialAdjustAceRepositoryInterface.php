<?php

namespace App\Repositories\MaterialUseAce;

interface MaterialAdjustAceRepositoryInterface
{
    public function saveBulk(array $rows): bool;
    public function existsPlanDate(string $transactionDate): bool;
    public function getCombinedReport(?string $startDate, ?string $endDate, ?string $materialCode = null);
}
