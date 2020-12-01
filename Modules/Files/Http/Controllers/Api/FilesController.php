<?php

namespace Modules\Files\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Files\Repositories\FilesRepository;
use Modules\Files\FileHelper;


/**
 * Class FilesController
 * @property FilesRepository $repository
 * @package Modules\Files\Http\Controllers\Api
 */
class FilesController extends BaseApiController
{
    /**
     * FilesController constructor.
     * @param FilesRepository $repository
     */
    public function __construct(FilesRepository $repository)
    {
        parent::__construct($repository);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function upload(Request $request)
    {
        $file = $request->file;

        $image_data = FileHelper::uploadFile($file, 'files');
        $image = FileHelper::saveImage($image_data, null, null);
        $path = $image_data['path_thumbnail'];
        $url = config('app.url') . $path;
        return response()->json([
            'url' => $url,
            'image' => $image
        ]);
    }


}
