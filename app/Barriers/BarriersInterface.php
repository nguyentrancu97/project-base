<?php

namespace App\Barriers;

/**
 * Interface BarriersInterface
 * @package App\Barriers
 */
interface BarriersInterface
{
    /**
     * @return bool
     */
    public function passes(): bool;

    /**
     * @return string
     */
    public function message(): string;
}
