<?php

namespace App\Http\Requests;

use App\Traits\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PredictionRequest extends FormRequest
{
    use ApiResponse;

    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $rules = [
            'home_score' => ['required', 'integer', 'min:0', 'max:50'],
            'away_score' => ['required', 'integer', 'min:0', 'max:50'],
        ];

        if ($this->isMethod('POST')) {
            $rules['match_id'] = ['required', 'integer', 'exists:matches,id'];
        }

        return $rules;
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            $this->error('Datos inválidos', $validator->errors(), 422)
        );
    }
}
