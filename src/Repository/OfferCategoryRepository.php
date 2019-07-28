<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\OfferCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method OfferCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method OfferCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method OfferCategory[]    findAll()
 * @method OfferCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OfferCategoryRepository extends ServiceEntityRepository
{
    const ALIAS = 'oc';

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, OfferCategory::class);
    }
}
