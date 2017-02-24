<?php
namespace DrdPlus\HuntingAndFishing;

use Drd\DiceRolls\Templates\Rolls\Roll2d6DrdPlus;
use DrdPlus\Calculations\SumAndRound;
use DrdPlus\Properties\Base\Knack;
use DrdPlus\Properties\Derived\Senses;
use DrdPlus\Tables\Measurements\Amount\Amount;
use DrdPlus\Tables\Measurements\Time\Time;
use Granam\Strict\Object\StrictObject;

/**
 * See PPH page 132, @link https://pph.drdplus.jaroslavtyc.com/#lov_a_rybolov
 */
class HuntingAndFishing extends StrictObject
{
    /**
     * @var int
     */
    private $huntPrerequisite;

    /**
     * @param Knack $knack
     * @param Senses $senses
     * @param HuntingAndFishingSkillBonus $huntingAndFishingSkillBonus
     * @param BonusFromDmForRolePlaying $bonusFromDmForRolePlaying
     */
    public function __construct(
        Knack $knack,
        Senses $senses,
        HuntingAndFishingSkillBonus $huntingAndFishingSkillBonus,
        BonusFromDmForRolePlaying $bonusFromDmForRolePlaying
    )
    {
        $this->huntPrerequisite = SumAndRound::half($knack->getValue() + $senses->getValue())
            + $huntingAndFishingSkillBonus->getValue() + $bonusFromDmForRolePlaying->getValue();
    }

    /**
     * @return int
     */
    public function getHuntPrerequisite(): int
    {
        return $this->huntPrerequisite;
    }

    /**
     * @param Roll2d6DrdPlus $roll2D6DrdPlus
     * @param Amount $requiredAmountOfMeals
     * @param Time $huntingTime
     * @return int
     * @throws \DrdPlus\HuntingAndFishing\Exceptions\HuntingTimeIsTooShort
     */
    public function getCatchQuality(
        Roll2d6DrdPlus $roll2D6DrdPlus,
        Amount $requiredAmountOfMeals,
        Time $huntingTime
    )
    {
        return SumAndRound::round(
            $this->getHuntPrerequisite()
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
    private function getModifierByHuntingTime(Time $time)
    {
        $timeBonusValue = $time->getBonus()->getValue();
        if ($timeBonusValue < 45) { // 30 minutes
            throw new Exceptions\HuntingTimeIsTooShort(
                "You can not hunt for less than 30 minutes, got time for hunt only {$time}"
            );
        }

        return $timeBonusValue - self::STANDARD_HUNTING_TIME_IN_BONUS;
    }
}