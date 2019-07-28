<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\OfferType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class OfferTypeFixtures extends Fixture
{
    public const OFFER_TYPE_REFERENCE = 'offer-type-%pos%';
    public const OFFER_TYPE_LENGTH = 'offer-type-length';

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $offerTypes = [
            new OfferType('CDI'),
            new OfferType('CDD'),
            new OfferType('Freelance'),
            new OfferType('Intérim'),
            new OfferType('Portage'),
        ];

        foreach ($offerTypes as $pos => $offerType) {
            $manager->persist($offerType);

            $this->addReference(
                str_replace('%pos%', $pos, self::OFFER_TYPE_REFERENCE),
                $offerType
            );

            $manager->flush();
        }
    }
}
