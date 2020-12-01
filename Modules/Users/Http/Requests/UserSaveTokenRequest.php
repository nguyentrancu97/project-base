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
class UserSaveTokenRequest extends ApiBaseRequest
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
            'user_id' => 'required|exists:Modules\Users\Entities\User,id',
            'fcm_token' => 'required'
        ];
    }
}
