<?php

namespace App\DataFixtures\Activity;

use App\Entity\Activity\Activity;
use App\Entity\Activity\PriceOption;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Tests\Helper\TestData;

class PriceOptionFixture extends Fixture implements DependentFixtureInterface
{
    public const PRICE_OPTION_REFERENCE = 'price';

    public function load(ObjectManager $manager)
    {
        $activities = $manager->getRepository(Activity::class)->findAll();

        $options = self::generate($activities)->return();
        foreach ($options as $object) {
            $manager->persist($object);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ActivityFixture::class,
        ];
    }

    public static function generate(array $activities): TestData
    {
        return TestData::from(new PriceOption())
            ->with('name', 'free')
            ->with('price', 0.00, 1.00)
            ->with('details', [])
            ->with('confirmationMsg', '')
            ->doWith('activity', function (PriceOption $priceOption, Activity $activity) {
                $priceOption->setActivity($activity);
            }, ...$activities)
        ;
    }
}
