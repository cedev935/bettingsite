<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class PlaceBetApiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'stake_amount' => ['required', 'numeric', 'between:0.3,10000',
                'min:0.3', 'max:10000'
            ],
            'selections' => 'required|array|min:1|max:20',
            'selections.*.odds' => ['required', 'numeric', 'min:1', 'max:10000'],
        ];
    }

    public function messages()
    {
        return [
            'stake_amount.min'=>'Minimum stake amount is :min',
            'stake_amount.max'=>'Maximum stake amount is :max',
            'selections.min' => 'Minimum number of selections is :min',
            'selections.max' => 'Minimum number of selections is :max',
            'selections.*.odds.min' => 'Minimum number of odd is :min',
            'selections.*.odds.max' => 'Maximum number of odd is :max'
        ];
    }
}
