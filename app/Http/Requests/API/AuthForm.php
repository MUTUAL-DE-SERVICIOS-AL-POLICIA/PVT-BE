<?php

namespace Muserpol\Http\Requests\API;

class AuthForm extends ApiRequest
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
            'identity_card' => 'required',
            'birth_date' => 'required',
            'device_id' => 'required|min:3|max:255',
            'firebase_token' => 'required'
        ];
    }
}
