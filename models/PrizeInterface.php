<?php

namespace app\models;

/**
 * Interface PrizeInterface
 * @package app\models
 */
interface PrizeInterface
{
    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @return string
     */
    public function getTitle(): string;
}