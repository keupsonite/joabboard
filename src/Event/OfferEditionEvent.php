<?php

declare(strict_types=1);

namespace App\Event;

use App\Entity\Offer;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class OfferEvent.
 */
class OfferEditionEvent extends Event
{
    public const NAME = 'offer.edition';

    /** @var Offer $offer */
    private $offer;

    /**
     * OfferApplianceEvent constructor.
     *
     * @param Offer $offer
     */
    public function __construct(Offer $offer)
    {
        $this->offer = $offer;

        return $this;
    }

    /**
     * @return Offer
     */
    public function getOffer()
    {
        return $this->offer;
    }
}
