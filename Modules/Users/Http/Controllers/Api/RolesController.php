<?php

namespace Modules\Users\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Users\Repositories\RoleRepository;
use Modules\Users\Transformers\RoleTransformer;

class RolesController extends BaseApiController
{

    public function __construct(RoleRepository $repository)
    {
        parent::__construct($repository);
    }

    /**
     * get list roles
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $roles = $this->repository->get();
        return $this->responseSuccess($this->transform($roles, RoleTransformer::class, $request));
    }


}
