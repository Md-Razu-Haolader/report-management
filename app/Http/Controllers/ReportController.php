<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ReportFilterRequest;
use App\Mail\Report;
use App\Services\Interfaces\DataProviderServiceInterface;
use App\Services\MailerService;
use Inertia\Inertia;

class ReportController extends Controller
{
    public function __construct(private DataProviderServiceInterface $dataProviderService)
    {
    }

    public function index()
    {
        $companySymbolList = collect($this->dataProviderService->getCompanyInfo())->pluck('Company Name', 'Symbol')->all();

        return Inertia::render('report/index', ['companySymbolList' => $companySymbolList]);
    }

    public function filter(ReportFilterRequest $request)
    {
        $symbol = $request->input('company_symbol');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $companySymbolList = collect($this->dataProviderService->getCompanyInfo())->pluck('Company Name', 'Symbol')->all();
        $historicalData = $this->dataProviderService->getHistoricalData($symbol, $startDate, $endDate);

        MailerService::send($request->input('email'), new Report([
            'start_date' => $startDate,
            'end_date' => $endDate,
            'company_name' => $companySymbolList[$symbol],
        ]));

        return Inertia::render('report/index', [
            'companySymbolList' => $companySymbolList,
            'historicalData' => $historicalData,
        ]);
    }
}
