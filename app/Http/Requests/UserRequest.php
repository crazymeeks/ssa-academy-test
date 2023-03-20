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
     * @return array<string, mixed>
     */
    public function rules()
    {
        // return $this->container->make(
        //     UserServiceInterface::class
        // )->rules($this->user);

        $rules = [
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|email|unique:users,email,' . $this->id,
            'password' => 'required',
            'username' => 'required|unique:users,username,' . $this->id,
        ];
        if ($this->id) {
            unset($rules['password']);
        }
        return $rules;
    }
}
