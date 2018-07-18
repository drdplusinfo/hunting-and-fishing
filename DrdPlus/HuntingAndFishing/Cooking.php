<?php
declare(strict_types = 1);

namespace DrdPlus\HuntingAndFishing;

interface Cooking
{
    /**
     * @return int
     */
    public function getBonus(): int;
}