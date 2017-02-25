<?php
namespace DrdPlus\Tests\HuntingAndFishing;

use DrdPlus\HuntingAndFishing\BonusFromDmForRolePlaying;
use DrdPlus\HuntingAndFishing\HuntPrerequisite;
use DrdPlus\HuntingAndFishing\HuntingAndFishingSkillBonus;
use DrdPlus\Properties\Base\Knack;
use DrdPlus\Properties\Derived\Senses;
use Granam\Integer\IntegerInterface;
use Granam\Tests\Tools\TestWithMockery;

class HuntingAndFishingTest extends TestWithMockery
{
    /**
     * @test
     */
    public function I_can_get_hunt_prerequisite()
    {
        $huntingAndFishing = new HuntPrerequisite(
            $this->createKnack(13),
            $this->createSenses(6),
            $this->createHuntingAndFishingSkillBonus(156),
            $this->createBonusFromDmForRolePlaying(237)
        );
        self::assertInstanceOf(IntegerInterface::class, $huntingAndFishing);
        self::assertSame(10 /* (13 + 6) / 2 */ + 156 + 237, $huntingAndFishing->getValue());
        self::assertSame((string)(10 /* (13 + 6) / 2 */ + 156 + 237), (string)$huntingAndFishing);
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
}