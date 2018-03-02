<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'name'=>'bail|min:3|max:100|required',
            'email'=>'bail|required|email|unique:users,email',
            'password'=>'bail|required|min:4|max:15',
            'perfil'=>'bail|required|string|in:staff,coordinador,admin',
            'circunscripcion'=>'bail|required|numeric|in:1,2,3,4,5',
            'estado'=>'bail|required|string',
            'active'=>'bail|boolean'
        ];
    }
}