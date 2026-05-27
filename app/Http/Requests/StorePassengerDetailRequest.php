<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePassengerDetailRequest extends FormRequest
{
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'passenger' => 'required|array|min:1',
            'passenger.*.name' => 'required',
            'passenger.*.date_of_birth' => 'required',
            'passenger.*.nationality' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'passenger.*.name' => 'Passenger name',
            'passenger.*.date_of_birth' => 'Passenger date of birth',
            'passenger.*.nationality' => 'Passenger nationality',
        ];
    }

    public function messages()
    {
        return [
            'passenger.*.name.required' => ':attribute field is required.',
            'passenger.*.date_of_birth.required' => ':attribute field is required.',
            'passenger.*.nationality.required' => ':attribute field is required.',
        ];
    }
}
