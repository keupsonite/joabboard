<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Event\OfferEditionEvent;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class OfferDraftSubscriber.
 */
class OfferEditionSubscriber implements EventSubscriberInterface
{
    /** @var EntityManager $manager */
    private $manager;

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            OfferEditionEvent::NAME => 'onOfferEdition',
        ];
    }

    /**
     * OfferSubscriber constructor.
     *
     * @param EntityManager $manager
     */
    public function __construct(EntityManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param OfferEditionEvent $event
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function onOfferEdition(OfferEditionEvent $event)
    {
        $offer = $event->getOffer();

        if ($offer->getStatus()) {
            $offer->setStatus(false);

            $this->manager->persist($offer);
            $this->manager->flush();
        }
    }
}
