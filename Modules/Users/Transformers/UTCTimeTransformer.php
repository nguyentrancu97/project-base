<?php
namespace Modules\Users\Transformers;

use League\Fractal\TransformerAbstract;
use Modules\Users\Entities\UTCTime;

class UTCTimeTransformer extends TransformerAbstract
{
    /**
     * @param UTCTime $UTCTime
     * @return array
     */

    public function transform(UTCTime $UTCTime)
    {
        return [
            'id' => $UTCTime->id,
            'name' => $UTCTime->name,
            'value' => $UTCTime->value
        ];
    }
}
