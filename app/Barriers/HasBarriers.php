<?php

namespace App\Barriers;

/**
 * Kiểm tra các điều kiện và điều hướng trước khi thực hiện xử lý logic trong controllers.
 *
 * ScheduleSupport HasBarriers
 * @package App\Barriers
 */
trait HasBarriers
{
    /**
     * @param BarriersInterface[] $objectives
     * @throws \Exception
     */
    public function barrier($objectives)
    {
        $objectives = is_array($objectives) ? $objectives : [$objectives];

        collect($objectives)->each(function (BarriersInterface $objective) {
            if (!$objective->passes()) {
                throw new \Exception($objective->message());
            }
        });
    }
}
