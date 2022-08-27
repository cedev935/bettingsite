<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class PlaceBetApiRequest extends FormRequest
{
    protected $errorBag = 'form';

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
            'stake_amount' => [
                'required',
                'numeric',
                'min:0.3',
                'max:10000',
                'between:0.3,10000',
            ],
            'selections' => ['required', 'array', 'min:1', 'max:20'],
            'selections.*.odds' => ['required', 'numeric', 'min:1', 'max:10000'],
        ];
    }

    public function messages()
    {
        return [
            'selections.*.odds.min' => 'Minimum number of odd is :min',
            'selections.*.odds.max' => 'Maximum number of odd is :max'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = new Response([
                'errors' => $validator->errors()]
            , 422);

        throw new ValidationException($validator, $response);
    }


}
