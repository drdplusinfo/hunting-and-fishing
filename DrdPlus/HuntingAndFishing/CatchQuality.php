<?php
namespace DrdPlus\HuntingAndFishing;

use Drd\DiceRolls\Templates\Rolls\Roll2d6DrdPlus;
use DrdPlus\Calculations\SumAndRound;
use DrdPlus\Tables\Measurements\Amount\Amount;
use DrdPlus\Tables\Measurements\Time\Time;
use Granam\Integer\IntegerInterface;
use Granam\Strict\Object\StrictObject;

/**
 * See PPH page 132, @link https://pph.drdplus.jaroslavtyc.com/#lov_a_rybolov
 */
class CatchQuality extends StrictObject implements IntegerInterface
{
    /** @var int */
    private $value;

    /**
     * @param HuntPrerequisite $huntPrerequisite
     * @param Roll2d6DrdPlus $roll2D6DrdPlus
     * @param Amount $requiredAmountOfMeals
     * @param Time $huntingTime
     * @throws \DrdPlus\HuntingAndFishing\Exceptions\HuntingTimeIsTooShort
     */
    public function __construct(
        HuntPrerequisite $huntPrerequisite,
        Roll2d6DrdPlus $roll2D6DrdPlus,
        Amount $requiredAmountOfMeals,
        Time $huntingTime
    )
    {
        $this->value = SumAndRound::round(
            $huntPrerequisite->getValue()
            - ($requiredAmountOfMeals->getBonus()->getValue() / 2)
            + $roll2D6DrdPlus->getValue()
            + $this->getModifierByHuntingTime($huntingTime)
        );
    }

    const STANDARD_HUNTING_TIME_IN_BONUS = 57; // 2 hours

    /**
     * @param Time $time
     * @return int
     * @throws \DrdPlus\HuntingAndFishing\Exceptions\HuntingTimeIsTooShort
     */
    private function getModifierByHuntingTime(Time $time): int
    {
        $timeBonusValue = $time->getBonus()->getValue();
        if ($timeBonusValue < 45 /* roughly 30 minutes */) {
            throw new Exceptions\HuntingTimeIsTooShort(
                "You can not hunt for less than 30 minutes, got time for hunt only {$time}"
            );
        }

        return $timeBonusValue - self::STANDARD_HUNTING_TIME_IN_BONUS;
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->getValue();
    }

}