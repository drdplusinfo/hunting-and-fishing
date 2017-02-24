<?php
namespace DrdPlus\Tests\HuntingAndFishing;

use DrdPlus\HuntingAndFishing\HuntingAndFishingSkillBonus;
use Granam\Integer\PositiveInteger;
use Granam\Tests\Tools\TestWithMockery;

class HuntingAndFishingSkillBonusTest extends TestWithMockery
{
    /**
     * @test
     */
    public function I_can_use_it_as_positive_integer()
    {
        self::assertTrue(is_a(HuntingAndFishingSkillBonus::class, PositiveInteger::class, true));
    }
}