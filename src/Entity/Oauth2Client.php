<?php

namespace App\Entity;

use App\Repository\Oauth2ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=Oauth2ClientRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Oauth2Client
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $secret;

    /**
     * @ORM\Column(type="text",nullable=true)
     */
    private $redirect;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\Column(type="text")
     */
    private $identifier;

    /**
     * @ORM\OneToMany(targetEntity=Oauth2AccessToken::class, mappedBy="client", orphanRemoval=true)
     */
    private $oauth2AccessTokens;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    public function __construct()
    {
        $this->oauth2AccessTokens = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSecret(): ?string
    {
        return $this->secret;
    }

    public function setSecret(string $secret): self
    {
        $this->secret = $secret;

        return $this;
    }

    public function getRedirect(): ?string
    {
        return $this->redirect;
    }

    public function setRedirect(string $redirect): self
    {
        $this->redirect = $redirect;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    public function setIdentifier(string $identifier): self
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * @return Collection|Oauth2AccessToken[]
     */
    public function getOauth2AccessTokens(): Collection
    {
        return $this->oauth2AccessTokens;
    }

    public function addOauth2AccessToken(Oauth2AccessToken $oauth2AccessToken): self
    {
        if (!$this->oauth2AccessTokens->contains($oauth2AccessToken)) {
            $this->oauth2AccessTokens[] = $oauth2AccessToken;
            $oauth2AccessToken->setClient($this);
        }

        return $this;
    }

    public function removeOauth2AccessToken(Oauth2AccessToken $oauth2AccessToken): self
    {
        if ($this->oauth2AccessTokens->contains($oauth2AccessToken)) {
            $this->oauth2AccessTokens->removeElement($oauth2AccessToken);
            // set the owning side to null (unless already changed)
            if ($oauth2AccessToken->getClient() === $this) {
                $oauth2AccessToken->setClient(null);
            }
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps(): void
    {
        $this->setUpdatedAt(new \DateTime('now'));
        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt(new \DateTime('now'));
        }
    }
}
