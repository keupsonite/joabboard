<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\OfferCategory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OfferCategoryTest extends WebTestCase
{
    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function testCreate()
    {
        self::bootKernel();

        $offerCategory = new OfferCategory();
        $this->assertTrue($offerCategory instanceof OfferCategory);

        $fluentIterator = $offerCategory->setName('Category');
        $this->assertTrue($fluentIterator instanceof OfferCategory);
        $this->assertSame('Category', $offerCategory->getName());

        $manager = self::$container->get('doctrine.orm.default_entity_manager');
        $manager->persist($offerCategory);
        $manager->flush();

        $this->assertNotNull($offerCategory->getId());
    }
}
