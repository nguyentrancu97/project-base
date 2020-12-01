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
class RatingRequest extends ApiBaseRequest
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
        $reason_other = 'nullable';
        if($this->request->get('type_rating') == 5) {
            $reason_other = 'required';
        }
        return [
            'rating' => 'required|numeric',
            'type_rating' => 'required|numeric|regex:/^[1-5]$/',
            'reason_other' => $reason_other
        ];
    }
}
