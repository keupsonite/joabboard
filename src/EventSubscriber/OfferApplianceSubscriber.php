<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Event\OfferApplianceEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Twig\Environment;

/**
 * Class OfferSubscriber.
 */
class OfferApplianceSubscriber implements EventSubscriberInterface
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    private $twig;

    private $senderEmail;

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            OfferApplianceEvent::NAME => 'onOfferAppliance',
        ];
    }

    /**
     * OfferSubscriber constructor.
     *
     * @param \Swift_Mailer $mailer
     * @param Environment   $twig
     * @param string        $senderEmail
     */
    public function __construct(\Swift_Mailer $mailer, Environment $twig, string $senderEmail)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->senderEmail = $senderEmail;
    }

    /**
     * @param OfferApplianceEvent $event
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function onOfferAppliance(OfferApplianceEvent $event): void
    {
        $offer = $event->getOffer();
        $candidateEmail = $event->getCandidate()->getEmail();

        $message = (new \Swift_Message())
            ->setSubject("New appliance for {$offer->getPosition()} - {$offer->getCategory()}")
            ->setFrom($this->senderEmail)
            ->setTo($candidateEmail)
            ->setBody(
                $this->twig->render('emails/offer/appliance.html.twig', ['email' => $candidateEmail]),
                'text/html'
            )
            ->addPart(
                $this->twig->render('emails/offer/appliance.txt.twig', ['email' => $candidateEmail]),
                'text/plain'
            )
        ;

        $this->mailer->send($message);
    }
}
