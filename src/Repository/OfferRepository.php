<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Offer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method Offer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Offer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Offer[]    findAll()
 * @method Offer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OfferRepository extends ServiceEntityRepository
{
    const ALIAS = 'o';

    /** @var string $userAlias */
    private $userAlias;

    /** @var string $offerTypeAlias */
    private $offerTypeAlias;

    /** @var string $offerCategoryAlias */
    private $offerCategoryAlias;

    /**
     * OfferRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Offer::class);

        $this->userAlias = UserRepository::ALIAS;
        $this->offerTypeAlias = OfferTypeRepository::ALIAS;
        $this->offerCategoryAlias = OfferCategoryRepository::ALIAS;
    }

    /**
     * @param QueryBuilder $query
     * @param string       $categoryFilter
     */
    public function filterQueryByCategory(QueryBuilder $query, string $categoryFilter): void
    {
        $query
            ->andWhere($this->offerCategoryAlias.'.name LIKE :category')
            ->setParameter('category', '%'.$categoryFilter.'%');
    }

    /**
     * @param QueryBuilder $query
     * @param string       $typeFilter
     */
    public function filterQueryByType(QueryBuilder $query, string $typeFilter): void
    {
        $query
            ->andWhere($this->offerTypeAlias.'.name LIKE :type')
            ->setParameter('type', '%'.$typeFilter.'%');
    }

    /**
     * @param bool               $isAnAdmin
     * @param bool               $isARecruiter
     * @param UserInterface|null $user
     *
     * @return QueryBuilder
     */
    public function getOffersQuery(bool $isAnAdmin, bool $isARecruiter, ?UserInterface $user): QueryBuilder
    {
        $query = $this
            ->createQueryBuilder($this::ALIAS)
            ->leftJoin($this::ALIAS.'.author', $this->userAlias)
            ->leftJoin($this::ALIAS.'.type', $this->offerTypeAlias)
            ->leftJoin($this::ALIAS.'.category', $this->offerCategoryAlias);

        if (!$isAnAdmin) {
            $query
                ->andWhere(
                    $this::ALIAS.'.status = :status'
                )
                ->setParameter('status', true);

            if ($isARecruiter) {
                $query
                    ->orWhere(
                        $this::ALIAS.'.author = :author'
                    )
                    ->setParameter('author', $user);
            }
        }

        return $query;
    }
}
