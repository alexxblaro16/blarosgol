<?php

namespace App\Http\Requests;

use App\Traits\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class CreateCommunityRequest extends FormRequest
{
    use ApiResponse;

    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:80',
                // Un usuario no puede crear 2 comunidades con el mismo nombre.
                Rule::unique('communities', 'name')->where(fn ($q) => $q->where('creator_id', $this->user()->id)),
            ],
            'description' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'Ya tienes una comunidad con ese nombre',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            $this->error('Datos inválidos', $validator->errors(), 422)
        );
    }
}
