<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\OfferType;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OfferTypeTest extends WebTestCase
{
    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function testCreate()
    {
        self::bootKernel();

        $offerType = new OfferType();
        $this->assertTrue($offerType instanceof OfferType);

        $fluentIterator = $offerType->setName('Type');
        $this->assertTrue($fluentIterator instanceof OfferType);
        $this->assertSame('Type', $offerType->getName());

        $manager = self::$container->get('doctrine.orm.default_entity_manager');
        $manager->persist($offerType);
        $manager->flush();

        $this->assertNotNull($offerType->getId());
    }
}
