<?php

declare(strict_types=1);

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OfferRepository")
 */
class Offer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\OfferType")
     * @Assert\NotNull
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\OfferCategory")
     * @Assert\NotNull
     */
    private $category;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $position;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User")
     */
    private $candidates;

    /**
     * Offer constructor.
     */
    public function __construct()
    {
        $this->candidates = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return User|null
     */
    public function getAuthor(): ?User
    {
        return $this->author;
    }

    /**
     * @param User|null $author
     *
     * @return Offer
     */
    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return OfferType|null
     */
    public function getType(): ?OfferType
    {
        return $this->type;
    }

    /**
     * @param OfferType|null $type
     *
     * @return Offer
     */
    public function setType(?OfferType $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return OfferCategory|null
     */
    public function getCategory(): ?OfferCategory
    {
        return $this->category;
    }

    /**
     * @param OfferCategory|null $category
     *
     * @return Offer
     */
    public function setCategory(?OfferCategory $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPosition(): ?string
    {
        return $this->position;
    }

    /**
     * @param string $position
     *
     * @return Offer
     */
    public function setPosition(string $position): self
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return Offer
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getCandidates(): Collection
    {
        return $this->candidates;
    }

    /**
     * @return bool|null
     */
    public function getStatus(): ?bool
    {
        return $this->status;
    }

    /**
     * @param bool $status
     *
     * @return Offer
     */
    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @param User $candidate
     *
     * @return Offer
     */
    public function addCandidate(User $candidate): self
    {
        if (!$this->candidates->contains($candidate)) {
            $this->candidates[] = $candidate;
        }

        return $this;
    }

    /**
     * @param User $candidate
     *
     * @return Offer
     */
    public function removeCandidate(User $candidate): self
    {
        if ($this->candidates->contains($candidate)) {
            $this->candidates->removeElement($candidate);
        }

        return $this;
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function isCandidate(User $user): bool
    {
        foreach ($this->getCandidates() as $candidate) {
            if ($candidate === $user) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function isAuthor(User $user): bool
    {
        return $this->getAuthor() === $user;
    }
}
