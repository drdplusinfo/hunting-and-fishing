<?php
namespace DrdPlus\Tests\HuntingAndFishing;

use Drd\DiceRolls\Templates\Rolls\Roll2d6DrdPlus;
use DrdPlus\HuntingAndFishing\CatchQuality;
use DrdPlus\HuntingAndFishing\HuntPrerequisite;
use DrdPlus\Tables\Measurements\Amount\Amount;
use DrdPlus\Tables\Measurements\Amount\AmountBonus;
use DrdPlus\Tables\Measurements\Time\Time;
use DrdPlus\Tables\Measurements\Time\TimeBonus;
use Granam\Integer\IntegerInterface;
use Granam\Tests\Tools\TestWithMockery;

class CatchQualityTest extends TestWithMockery
{
    /**
     * @test
     * @dataProvider provideValuesToGetCatchQuality
     * @param int $givenHuntPrerequisite
     * @param int $rollValue
     * @param int $requiredAmountOfMealsValue
     * @param int $huntingTimeInBonus
     * @param int $expectedCatchQuality
     */
    public function I_can_get_catch_quality(
        int $givenHuntPrerequisite,
        int $rollValue,
        int $requiredAmountOfMealsValue,
        int $huntingTimeInBonus,
        int $expectedCatchQuality
    )
    {
        $catchQuality = new CatchQuality(
            $this->createHuntPrerequisite($givenHuntPrerequisite),
            $this->createRoll2d6DrdPlus($rollValue),
            $this->createRequiredAmountOfMealsInBonus($requiredAmountOfMealsValue),
            $this->createHuntingTimeInBonus($huntingTimeInBonus)
        );
        self::assertInstanceOf(IntegerInterface::class, $catchQuality);
        self::assertSame($expectedCatchQuality, $catchQuality->getValue());
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
     * @return \Mockery\MockInterface|HuntPrerequisite
     */
    private function createHuntPrerequisite(int $value)
    {
        $huntPrerequisite = $this->mockery(HuntPrerequisite::class);
        $huntPrerequisite->shouldReceive('getValue')
            ->andReturn($value);

        return $huntPrerequisite;
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