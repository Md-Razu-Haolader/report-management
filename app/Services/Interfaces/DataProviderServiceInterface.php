<?php

declare(strict_types=1);

namespace App\Services\Interfaces;

interface DataProviderServiceInterface
{
    public function getCompanyInfo(): array;

    public function getHistoricalData(string $symbol, string $startDate, string $endData): array;
}
