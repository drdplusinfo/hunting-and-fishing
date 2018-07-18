<?php
declare(strict_types = 1);

namespace DrdPlus\HuntingAndFishing;

interface WithBonusFromHuntingAndFishingSkill
{
    /**
     * @return int
     */
    public function getBonusFromSkill(): int;
}