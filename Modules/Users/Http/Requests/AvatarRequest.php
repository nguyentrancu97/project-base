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
class AvatarRequest extends ApiBaseRequest
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
            'avatar' => 'required|image',
        ];
    }
}
