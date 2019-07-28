<?php

declare(strict_types=1);

namespace App\Event;

use App\Entity\Offer;
use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class OfferApplianceEvent.
 */
class OfferApplianceEvent extends Event
{
    public const NAME = 'offer.appliance';

    /** @var Offer $offer */
    private $offer;

    /** @var User $candidate */
    private $candidate;

    /**
     * OfferApplianceEvent constructor.
     *
     * @param Offer $offer
     * @param User  $appliedCandidate
     */
    public function __construct(Offer $offer, User $appliedCandidate)
    {
        $this->offer = $offer;
        $this->candidate = $appliedCandidate;

        return $this;
    }

    /**
     * @return Offer
     */
    public function getOffer()
    {
        return $this->offer;
    }

    public function getCandidate()
    {
        return $this->candidate;
    }
}
