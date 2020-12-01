<?php

namespace App\Http\Controllers\Api\Traits;

use Illuminate\Http\JsonResponse;

/**
 * ScheduleSupport ResponseTrait
 * @package App\Http\Controllers\Api\Traits
 */
trait ResponseTrait
{
    /**
     * @param array $data
     * @param int $code
     * @param string $message
     * @return JsonResponse
     */
    protected function responseSuccess($data = [], $code = 200, $message = "")
    {
        return response()->json(array_merge_recursive([
            'meta' => [
                'code' => $code,
                'message' => $message != "" ? $message : $this->getMessage($code)
            ]
        ], $this->formatData($data)));
    }

    /**
     * @param int $code
     * @param string $message
     * @param null $data
     * @return JsonResponse
     */
    protected function responseErrors($code = 400, $message = "", $data = null)
    {
        return response()->json(array_merge_recursive([
            'meta' => [
                'code' => $code,
                'message' => $message != "" ? $message : $this->getMessage($code)
            ]
        ], $this->formatData($data)))->setStatusCode($code);
    }

    /**
     * @param $code
     * @return string
     */
    protected function getMessage($code)
    {
        switch ($code) {
            case 400:
                $message = 'Invalid data';
                break;
            case 401:
                $message = 'Unauthorized';
                break;
            case 404:
                $message = 'Not found';
                break;
            case 500:
                $message = 'Internal Server Error';
                break;
            case 200:
                $message = 'Success';
                break;
            default:
                $message = '';
        }
        return $message;
    }

    protected function formatData($data)
    {
        return is_array($data) && array_key_exists('data', $data)
            ? $data
            : ['data' => $data];
    }
}
