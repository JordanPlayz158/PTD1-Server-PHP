<?php

namespace App\Http\Requests\Web\SWF;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SWFRequest extends FormRequest
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
            // Uncomment when all users migrate to proper email addresses
            //'email' => ['required', 'string', 'email'],
            'Email' => ['required', 'string'],
            'Pass' => ['required', 'string'],
            'ver' => ['integer', 'numeric'],
            'Action' => ['required', 'string']
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @return bool
     *
     */
    public function authenticate() : bool
    {
        return Auth::attempt(['email' => $this->input('Email'), 'password' => $this->input('Pass')]);
    }
}
