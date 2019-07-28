<?php

namespace App\Security;

use App\Entity\Offer;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

/**
 * Class OfferVoter.
 */
class OfferVoter extends Voter
{
    /** @var ?User $user */
    private $user;

    /** @var Security $security */
    private $security;

    const VIEW = 'view';
    const EDIT = 'edit';
    const APPLY = 'apply';

    private $attributes = [
        self::VIEW,
        self::EDIT,
        self::APPLY,
    ];

    /**
     * OfferVoter constructor.
     *
     * @param Security $security
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * Determines if the attribute and subject are supported by this voter.
     *
     * @param string $attribute An attribute
     * @param mixed  $subject   The subject to secure, e.g. an object the user wants to access or any other PHP type
     *
     * @return bool True if the attribute and subject are supported, false otherwise
     */
    protected function supports($attribute, $subject): bool
    {
        if (!in_array($attribute, $this->attributes)) {
            return false;
        }

        if (!$subject instanceof Offer) {
            return false;
        }

        return true;
    }

    /**
     * Perform a single access check operation on a given attribute, subject and token.
     * It is safe to assume that $attribute and $subject already passed the "supports()" method check.
     *
     * @param string         $attribute
     * @param mixed          $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        $this->user = $token->getUser();

        /** @var Offer $offer */
        $offer = $subject;

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($offer);
            case self::EDIT:
                return $this->canEdit($offer);
            case self::APPLY:
                return $this->canApply($offer);
        }

        throw new \LogicException('This code should not be reached!');
    }

    /***
     * @param Offer $offer
     * @return bool
     */
    private function canView(Offer $offer): bool
    {
        if ($offer->getStatus()) {
            return true;
        }

        if ($this->canEdit($offer)) {
            return true;
        }

        return false;
    }

    /**
     * @param Offer $offer
     *
     * @return bool
     */
    private function canEdit(Offer $offer): bool
    {
        if (!$this->user instanceof User) {
            return false;
        }

        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        return $this->user === $offer->getAuthor();
    }

    /**
     * @param Offer $offer
     *
     * @return bool
     */
    private function canApply(Offer $offer): bool
    {
        if (!$this->user instanceof User) {
            return false;
        }

        if (!$offer->getStatus()) {
            return false;
        }

        if ($offer->isCandidate($this->user) || $offer->isAuthor($this->user)) {
            return false;
        }

        return $this->security->isGranted('ROLE_CANDIDATE');
    }
}
