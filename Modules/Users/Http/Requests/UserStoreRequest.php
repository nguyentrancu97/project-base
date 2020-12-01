<?php

namespace Modules\Users\Http\Requests;


use App\Http\Requests\ApiBaseRequest;

/**
 * Class UserStoreRequest
 * @package Modules\Users\Http\Requests
 */
class UserStoreRequest extends ApiBaseRequest
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
        $password = 'required';
        if($this->get('role_id') == 3 || $this->get('role_id') == 4) {
            $password = 'nullable';
        }
        return [
            'name' => 'required',
            'email' => 'required|email:rfc,dns|unique:users,email',
            'password' => $password,
            'role_id' => 'required|exists:Spatie\Permission\Models\Role,id',
            'time_id' => 'required|exists:Modules\Users\Entities\UTCTime,id'
        ];
    }
}
