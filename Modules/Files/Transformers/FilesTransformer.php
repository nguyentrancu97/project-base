<?php

namespace Modules\Files\Transformers;

use Carbon\Carbon;
use League\Fractal\TransformerAbstract;
use Modules\Files\Entities\Files;

class FilesTransformer extends TransformerAbstract
{

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [];

    public function transform(Files $files)
    {
        return [
            'id' => $files->id,
            'name' => $files->name,
            'path' => $files->path,
            'path_thumbnail' => $files->path_thumbnail,
        ];
    }
}
