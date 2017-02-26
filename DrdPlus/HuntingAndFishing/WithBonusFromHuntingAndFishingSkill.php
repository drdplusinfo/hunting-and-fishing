<?php
namespace DrdPlus\HuntingAndFishing;

interface WithBonusFromHuntingAndFishingSkill
{
    /**
     * @return int
     */
    public function getBonusFromSkill(): int;
}