<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Offer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class OfferFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @param $collection
     *
     * @return object
     *
     * @throws \LogicException
     */
    private function pickOne($collection): object
    {
        $random = rand(0, (count($collection) - 1));

        if (!array_key_exists($random, $collection)) {
            throw new \LogicException("Fixture #{$random} object not found in DB.");
        }

        return $collection[$random];
    }

    /**
     * @return array
     */
    private function getOfferRecruiters(): array
    {
        return [
            $this->getReference(UserFixtures::RECRUITER_REFERENCE),
            $this->getReference(UserFixtures::RECRUITER2_REFERENCE),
        ];
    }

    /**
     * @return array
     */
    private function getOfferCategories(): array
    {
        $i = 0;
        $offerCategoryReference = OfferCategoryFixtures::OFFER_CATEGORY_REFERENCE;
        $categories = [];

        while ($this->hasReference(($reference = str_replace('%pos%', $i++, $offerCategoryReference)))) {
            $categories[] = $this->getReference($reference);
        }

        return $categories;
    }

    /**
     * @return array
     */
    private function getOfferTypes(): array
    {
        $i = 0;
        $offerCategoryReference = OfferTypeFixtures::OFFER_TYPE_REFERENCE;
        $types = [];

        while ($this->hasReference(($reference = str_replace('%pos%', $i++, $offerCategoryReference)))) {
            $types[] = $this->getReference($reference);
        }

        return $types;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        $recruiters = $this->getOfferRecruiters();
        $types = $this->getOfferTypes();
        $categories = $this->getOfferCategories();

        for ($i = 0; $i < 200; ++$i) {
            $offer = (new Offer())
                ->setAuthor($this->pickOne($recruiters))
                ->setType($this->pickOne($types))
                ->setCategory($this->pickOne($categories))
                ->setPosition($faker->jobTitle)
                ->setDescription($faker->paragraph)
                ->setStatus($faker->boolean)
            ;

            $manager->persist($offer);
        }

        $manager->flush();
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            OfferTypeFixtures::class,
            OfferCategoryFixtures::class,
        ];
    }
}
