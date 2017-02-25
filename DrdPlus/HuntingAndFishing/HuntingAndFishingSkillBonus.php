<?php
namespace DrdPlus\HuntingAndFishing;

interface HuntingAndFishingSkillBonus
{
    /**
     * @return int
     */
    public function getBonusFromSkill(): int;
}