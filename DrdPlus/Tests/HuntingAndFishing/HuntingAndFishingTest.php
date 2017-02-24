<?php
namespace DrdPlus\Tests\HuntingAndFishing;

use Drd\DiceRolls\Templates\Rolls\Roll2d6DrdPlus;
use DrdPlus\HuntingAndFishing\BonusFromDmForRolePlaying;
use DrdPlus\HuntingAndFishing\HuntingAndFishing;
use DrdPlus\HuntingAndFishing\HuntingAndFishingSkillBonus;
use DrdPlus\Properties\Base\Knack;
use DrdPlus\Properties\Derived\Senses;
use DrdPlus\Tables\Measurements\Amount\Amount;
use DrdPlus\Tables\Measurements\Amount\AmountBonus;
use DrdPlus\Tables\Measurements\Time\Time;
use DrdPlus\Tables\Measurements\Time\TimeBonus;
use Granam\Tests\Tools\TestWithMockery;

class HuntingAndFishingTest extends TestWithMockery
{
    /**
     * @test
     */
    public function I_can_get_hunt_prerequisite()
    {
        $huntingAndFishing = new HuntingAndFishing(
            $this->createKnack(13),
            $this->createSenses(6),
            $this->createHuntingAndFishingSkillBonus(156),
            $this->createBonusFromDmForRolePlaying(237)
        );
        self::assertSame(10 /* (13 + 6) / 2 */ + 156 + 237, $huntingAndFishing->getHuntPrerequisite());
    }

    /**
     * @param int $value
     * @return \Mockery\MockInterface|Knack
     */
    private function createKnack(int $value)
    {
        $knack = $this->mockery(Knack::class);
        $knack->shouldReceive('getValue')
            ->andReturn($value);

        return $knack;
    }

    /**
     * @param int $value
     * @return \Mockery\MockInterface|Senses
     */
    private function createSenses(int $value)
    {
        $senses = $this->mockery(Senses::class);
        $senses->shouldReceive('getValue')
            ->andReturn($value);

        return $senses;
    }

    /**
     * @param int $value
     * @return \Mockery\MockInterface|HuntingAndFishingSkillBonus
     */
    private function createHuntingAndFishingSkillBonus(int $value)
    {
        $huntingAndFishingSkillBonus = $this->mockery(HuntingAndFishingSkillBonus::class);
        $huntingAndFishingSkillBonus->shouldReceive('getValue')
            ->andReturn($value);

        return $huntingAndFishingSkillBonus;
    }

    /**
     * @param int $value
     * @return \Mockery\MockInterface|BonusFromDmForRolePlaying
     */
    private function createBonusFromDmForRolePlaying(int $value)
    {
        $bonusFromDmForRolePlaying = $this->mockery(BonusFromDmForRolePlaying::class);
        $bonusFromDmForRolePlaying->shouldReceive('getValue')
            ->andReturn($value);

        return $bonusFromDmForRolePlaying;
    }

    /**
     * @test
     * @dataProvider provideValuesToGetCatchQuality
     * @param int $expectedHuntPrerequisite
     * @param int $rollValue
     * @param int $requiredAmountOfMealsValue
     * @param int $huntingTimeInBonus
     * @param int $expectedCatchQuality
     */
    public function I_can_get_catch_quality(
        int $expectedHuntPrerequisite,
        int $rollValue,
        int $requiredAmountOfMealsValue,
        int $huntingTimeInBonus,
        int $expectedCatchQuality
    )
    {
        $huntingAndFishing = new HuntingAndFishing(
            $this->createKnack(1),
            $this->createSenses(2),
            $this->createHuntingAndFishingSkillBonus(3),
            $this->createBonusFromDmForRolePlaying(4)
        );
        self::assertSame($expectedHuntPrerequisite, $huntingAndFishing->getHuntPrerequisite());
        $catchQuality = $huntingAndFishing->getCatchQuality(
            $this->createRoll2d6DrdPlus($rollValue),
            $this->createRequiredAmountOfMealsInBonus($requiredAmountOfMealsValue),
            $this->createHuntingTimeInBonus($huntingTimeInBonus)
        );
        self::assertSame($expectedCatchQuality, $catchQuality);
    }

    public function provideValuesToGetCatchQuality()
    {
        // required hunt prerequisite, roll, amount of meals as amount bonus, hunting time as time bonus, expected catch quality
        return [
            [9, 13, 21, 57 /* 2 hours */, 12],
            [9, 13, 21, 50, 5],
            [9, 13, 21, 80, 35],
        ];
    }

    /**
     * @param int $value
     * @return \Mockery\MockInterface|Roll2d6DrdPlus
     */
    private function createRoll2d6DrdPlus(int $value)
    {
        $roll2d6DrdPlus = $this->mockery(Roll2d6DrdPlus::class);
        $roll2d6DrdPlus->shouldReceive('getValue')
            ->andReturn($value);

        return $roll2d6DrdPlus;
    }

    /**
     * @param int $amountBonusValue
     * @return \Mockery\MockInterface|Amount
     */
    private function createRequiredAmountOfMealsInBonus(int $amountBonusValue)
    {
        $amountOfMeals = $this->mockery(Amount::class);
        $amountOfMeals->shouldReceive('getBonus')
            ->andReturn($amountBonus = $this->mockery(AmountBonus::class));
        $amountBonus->shouldReceive('getValue')
            ->andReturn($amountBonusValue);

        return $amountOfMeals;
    }

    /**
     * @param int $huntingTimeBonusValue
     * @return \Mockery\MockInterface|Time
     */
    private function createHuntingTimeInBonus(int $huntingTimeBonusValue)
    {
        $time = $this->mockery(Time::class);
        $time->shouldReceive('getBonus')
            ->andReturn($timeBonus = $this->mockery(TimeBonus::class));
        $timeBonus->shouldReceive('getValue')
            ->andReturn($huntingTimeBonusValue);

        return $time;
    }
}