<?php

namespace Modules\Users\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Users\Transformers\UTCTimeTransformer;
use Modules\Users\Repositories\UTCTimeRepository;

class UTCTimeController extends BaseApiController
{


    /**
     * UTCTimeController constructor.
     * @param UTCTimeRepository $repository
     */
    public function __construct(UTCTimeRepository $repository)
    {
        parent::__construct($repository);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $UTCTime = $this->repository->get();
        return $this->responseSuccess($this->transform($UTCTime, UTCTimeTransformer::class, $request));
    }
}
