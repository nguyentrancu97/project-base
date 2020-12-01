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
class UserUpdateRequest extends ApiBaseRequest
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
            'email' => 'required|email:rfc,dns|unique:users,email,'.$this->route('user_id').',id',
            'role_id' => 'required|exists:Spatie\Permission\Models\Role,id',
            'time_id' => 'required|exists:Modules\Users\Entities\UTCTime,id',
            'course_ids' => 'required','array'
        ];
    }
}
