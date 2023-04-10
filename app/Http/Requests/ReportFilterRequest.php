<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Services\Interfaces\DataProviderServiceInterface;
use Illuminate\Foundation\Http\FormRequest;

class ReportFilterRequest extends FormRequest
{
    public function __construct(private DataProviderServiceInterface $dataProviderService)
    {
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $companySymbolList = collect($this->dataProviderService->getCompanyInfo())->pluck('Symbol')->all();
        return [
            'company_symbol' => 'required|in:' . implode(',', $companySymbolList),
            'start_date' => 'required|date|before_or_equal:end_date|before_or_equal:now',
            'end_date' => 'required|date|after_or_equal:start_date|before_or_equal:now',
            'email' => 'required|email',
        ];
    }
}
