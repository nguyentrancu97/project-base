<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Traits\ResponseTrait;
use App\Http\Controllers\Controller;
use App\Transformers\Fractal\CustomScopeFactory;
use App\Transformers\Fractal\Serializer\CustomArraySerializer;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 *
 * @OA\Server(
 *     url="/api"
 * )
 *
 * @OA\Info(
 *      title="MinhViet Core API",
 *      version="1.0.0",
 *      @OA\Contact(
 *          email="nhiembv@gmail.com"
 *      )
 * )
 *
 * @OA\SecurityScheme(
 *      securityScheme="bearerAuth",
 *      in="header",
 *      name="bearerAuth",
 *      type="http",
 *      scheme="bearer",
 *      bearerFormat="JWT",
 * )
 *
 */

/**
 * Class BaseApiController
 * @property BaseRepository $repository
 * @package App\Http\Controllers\Api
 */
abstract class BaseApiController extends Controller
{
    use ResponseTrait;

    protected $fractal;

    protected $defaultInclude = [];

    protected $defaultExclude = [];

    protected $repository;


    /**
     * BaseApiController constructor.
     * @param $repository
     */

    public function __construct($repository)
    {
        $this->repository = $repository;

        $this->fractal = new Manager(app(CustomScopeFactory::class));

        $this->fractal->setSerializer(new CustomArraySerializer());
    }

    /**
     * @param LengthAwarePaginator|EloquentCollection|object $data
     * @param string $transformer
     * @param Request $request
     * @return array
     */
    protected function transform($data, $transformer, Request $request)
    {
        if ($data instanceof LengthAwarePaginator) {
            $result = new Collection($data->items(), app($transformer));
            $result->setPaginator(new IlluminatePaginatorAdapter($data));
            $result->setMetaValue('total', $data->total());
        } elseif ($data instanceof EloquentCollection || is_array($data)) {
            $result = new Collection($data, app($transformer));
            $result->setMetaValue('total', count($data));
        } else {
            $result = new Item($data, app($transformer));
        }

        $this->parseInclude($request);

        $this->parseExclude($request);

        return $this->fractal->createData($result)->toArray();
    }

    /**
     * @param Request $request
     */
    protected function parseInclude(Request $request)
    {
        foreach ($this->defaultInclude as $include) {
            $this->fractal->parseIncludes($include);
        }

        if ($request->get('include')) {
            $this->fractal->parseIncludes(explode(',', $request->get('include')));
        }
    }

    /**
     * @param Request $request
     */
    protected function parseExclude(Request $request)
    {
        foreach ($this->defaultExclude as $include) {
            $this->fractal->parseExcludes($include);
        }

        if ($request->get('exclude')) {
            $this->fractal->parseExcludes(explode(',', $request->get('exclude')));
        }
    }
}
