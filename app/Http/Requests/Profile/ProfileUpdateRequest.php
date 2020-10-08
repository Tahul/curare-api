<?php

namespace App\Http\Requests\Profile;

use App\Http\Requests\ApiFormRequest;

class ProfileUpdateRequest extends ApiFormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => ['string', 'max:255'],
            'last_name' => ['string', 'max:255'],
            'description' => ['string'],
            'url' => ['string']
        ];
    }
}
