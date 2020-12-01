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
class UserUpdateMe extends ApiBaseRequest
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
            'name' => 'required',
            'email' => 'required|email:rfc,dns|unique:users,email,'.auth('admin')->user()->id.',id',
            'role_id' => 'required|exists:Spatie\Permission\Models\Role,id',
            'time_id' => 'required|exists:Modules\Users\Entities\UTCTime,id'
        ];
    }
}
