<?php

declare(strict_types=1);

namespace App\Services;

use App\Services\Interfaces\DataProviderServiceInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;

class DataProviderService implements DataProviderServiceInterface
{
    private string $apiKey;
    private string $apiHost;

    private const COMPANY_INFO_ENDPOINT = 'https://pkgstore.datahub.io/core/nasdaq-listings/nasdaq-listed_json/data/a5bc7580d6176d60ac0b2142ca8d7df6/nasdaq-listed_json.json';
    private const HISTORICAL_DATA_ENDPOINT = 'https://yh-finance.p.rapidapi.com/stock/v3/get-historical-data';

    public function __construct()
    {
        $this->apiKey = Config::get('common.rapid_api_key');
        $this->apiHost = Config::get('common.rapid_api_host');
    }

    public function getCompanyInfo(): array
    {
        $response = Http::get(self::COMPANY_INFO_ENDPOINT);
        if ($response->ok()) {
            return $response->json();
        } else {
            throw new \Exception('Failed to fetch company info');
        }
    }

    public function getHistoricalData(string $symbol, string $startDate, string $endData): array
    {
        $response = Http::withHeaders([
            'X-RapidAPI-Key' => $this->apiKey,
            'X-RapidAPI-Host' => $this->apiHost,
        ])->get(self::HISTORICAL_DATA_ENDPOINT . '?symbol=' . $symbol);

        if ($response->ok()) {
            $prices = $response->json()['prices'] ?? [];

            return collect($prices)->whereBetween('date', [
                strtotime($startDate),
                strtotime($endData . ' 23:59:59'),
            ])->values()->all();
        } else {
            throw new \Exception('Historical data not found');
        }
    }
}
