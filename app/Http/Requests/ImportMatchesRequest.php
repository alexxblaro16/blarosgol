<?php

namespace App\Http\Requests;

use App\Traits\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ImportMatchesRequest extends FormRequest
{
    use ApiResponse;

    public function authorize(): bool
    {
        return $this->user() && $this->user()->is_admin;
    }

    public function rules(): array
    {
        return [
            'file' => ['required_without:matches', 'file', 'mimetypes:text/csv,text/plain,application/json,application/octet-stream', 'max:4096'],
            'matches' => ['required_without:file', 'array'],
            'matches.*.phase_code' => ['required_with:matches', 'string'],
            'matches.*.home_team' => ['required_with:matches', 'string'],
            'matches.*.away_team' => ['required_with:matches', 'string'],
            'matches.*.kick_off_at' => ['required_with:matches', 'date'],
            'matches.*.venue' => ['nullable', 'string'],
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            $this->error('Datos inválidos', $validator->errors(), 422)
        );
    }
}
