<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Repository\OfferRepository;
use App\Service\OffersQueryService;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Security;

/**
 * Class OffersQueryServiceTest.
 */
class OffersQueryServiceTest extends WebTestCase
{
    /**
     * @return OffersQueryService
     */
    private function getInitializedService(): OffersQueryService
    {
        self::bootKernel();

        $request = new Request();
        $request->setSession(
            new Session(
                new MockArraySessionStorage()
            )
        );

        $requestStack = new RequestStack();
        $requestStack->push($request);

        return new OffersQueryService(
            $requestStack,
            self::$container->get(OfferRepository::class),
            self::$container->get(Security::class),
            $this->createMock(AuthorizationCheckerInterface::class)
        );
    }

    public function testGetFilteredQueryAsAnonymous()
    {
        $service = $this->getInitializedService();

        $this->assertTrue($service->getFilteredQuery() instanceof QueryBuilder);
    }
}
