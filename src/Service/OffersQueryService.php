<?php

declare(strict_types=1);

namespace App\Service;

use App\Repository\OfferRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class OffersQueryService.
 */
class OffersQueryService
{
    /** @var Request $request */
    private $request;

    /** @var OfferRepository */
    private $repository;

    /** @var \Doctrine\ORM\QueryBuilder */
    private $query;

    /**
     * OffersQueryService constructor.
     *
     * @param RequestStack                  $requestStack
     * @param OfferRepository               $repository
     * @param Security                      $security
     * @param AuthorizationCheckerInterface $checker
     */
    public function __construct(
        RequestStack $requestStack,
        OfferRepository $repository,
        Security $security,
        AuthorizationCheckerInterface $checker
    ) {
        $this->request = $requestStack->getCurrentRequest();
        $this->repository = $repository;
        $this->query = $this->repository->getOffersQuery(
            $checker->isGranted('ROLE_ADMIN') ?? false,
            $checker->isGranted('ROLE_RECRUITER') ?? false,
            $security->getUser() ?? null
        );
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getFilteredQuery(): QueryBuilder
    {
        $categoryFilter = $this->request->query->filter('category', '', FILTER_SANITIZE_STRING);
        $typeFilter = $this->request->query->filter('type', '', FILTER_SANITIZE_STRING);

        if (strlen($categoryFilter) > 0) {
            $this->repository->filterQueryByCategory($this->query, $categoryFilter);
        }

        if (strlen($typeFilter) > 0) {
            $this->repository->filterQueryByType($this->query, $typeFilter);
        }

        return $this->query;
    }
}
