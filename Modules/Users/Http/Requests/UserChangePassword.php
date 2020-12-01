<?php

namespace Modules\Users\Http\Requests;


use App\Http\Requests\ApiBaseRequest;
use Illuminate\Support\Facades\Auth;


/**
 * Lead API Request
 *
 * Class LeadApiRequest
 * @package Modules\Users\Http\Requests
 */
class UserChangePassword extends ApiBaseRequest
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
     * @return array|string[]
     */
    public function rules()
    {
        return [
            'current_password' => 'required',
            'new_password' => 'required|min:6|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'required|min:6',
        ];
    }
}
