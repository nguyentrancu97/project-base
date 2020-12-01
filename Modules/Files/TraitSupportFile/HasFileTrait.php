<?php

namespace Modules\Files\TraitSupportFile;

use Modules\Files\Entities\Files;

trait HasFileTrait {

    /**
     * @return mixed
     */
    public function file()
    {
        return $this->morphOne(Files::class, 'fileable');
    }

    /**
     * @return mixed
     */
    public function files()
    {
        return $this->morphMany(Files::class, 'fileable');
    }

    /**
     * @param $fileIds
     */
    public function updateFile($fileIds = [])
    {
        $fileIdsCurrent = Files::query()
            ->where([
                'fileable_id' => $this->id,
                'fileable_type' => $this->getModel()
            ])
            ->pluck('id')
            ->toArray();
        $IdsDiff = array_diff($fileIdsCurrent, $fileIds);
        Files::query()->whereIn('id', $IdsDiff)->delete();
    }

}
