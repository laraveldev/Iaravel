<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BronRequest extends FormRequest
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
             
            'user_id' => 'required|exists:users,id',
            'venue_id' => 'required|exists:venues,id',
            'service_id' => 'required|exists:services,id',
            'event_date' => 'required|date|after:today',
            'event_time' => 'required|date_format:H:i',
            'guests_count' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        
        ];
    }
}
