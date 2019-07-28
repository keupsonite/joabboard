<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\OfferCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class OfferCategoryFixtures extends Fixture
{
    public const OFFER_CATEGORY_REFERENCE = 'offer-category-%pos%';
    public const OFFER_CATEGORY_LENGTH = 'offer-category-length';

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $offerCategories = [
            new OfferCategory('Audit / Finance / Assurance'),
            new OfferCategory('Business'),
            new OfferCategory('Conseil'),
            new OfferCategory('Créa'),
            new OfferCategory('Hôtellerie / Restauration'),
        ];

        foreach ($offerCategories as $i => $offerCategory) {
            $manager->persist($offerCategory);

            $this->addReference(
                str_replace('%pos%', $i, self::OFFER_CATEGORY_REFERENCE),
                $offerCategory
            );

            $manager->flush();
        }
    }
}
