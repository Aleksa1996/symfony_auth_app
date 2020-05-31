<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use App\Dto\User\UserOutput;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity("email",message="Email already used. Please choose another one.")
 * @UniqueEntity("username",message="Username already used. Please choose another one.")
 * @ApiResource(
 *     output=UserOutput::class,
 *     collectionOperations={
 *          "get",
 *          "post"={"security"="is_granted('ROLE_ADMIN')"}
 *     },
 *     itemOperations={
 *         "get",
 *         "put"={"security"="is_granted('ROLE_ADMIN') or object == user"},
 *     }
 * )
 *
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $username;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\NotBlank
     * @Assert\Length(min="5",max="25")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $avatar;

    /**
     * @ORM\OneToMany(targetEntity=Oauth2AccessToken::class, mappedBy="user", orphanRemoval=true)
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

    /**
     * Get email
     *
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return $this
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * set username
     *
     * @param string $username
     * @return $this
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get roles
     *
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * Set roles
     *
     * @param array $roles
     * @return $this
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Get password
     *
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string)$this->password;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return $this
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * Get avatar
     *
     * @return string|null
     */
    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    /**
     * Set avatar
     *
     * @param string|null $avatar
     * @return $this
     */
    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * @return Collection|Oauth2AccessToken[]
     */
    public function getOauth2AccessTokens(): Collection
    {
        return $this->oauth2AccessTokens;
    }

    /**
     * Attach token to user
     *
     * @param Oauth2AccessToken $oauth2AccessToken
     * @return $this
     */
    public function addOauth2AccessToken(Oauth2AccessToken $oauth2AccessToken): self
    {
        if (!$this->oauth2AccessTokens->contains($oauth2AccessToken)) {
            $this->oauth2AccessTokens[] = $oauth2AccessToken;
            $oauth2AccessToken->setUser($this);
        }

        return $this;
    }

    /**
     * Detach token from user
     *
     * @param Oauth2AccessToken $oauth2AccessToken
     * @return $this
     */
    public function removeOauth2AccessToken(Oauth2AccessToken $oauth2AccessToken): self
    {
        if ($this->oauth2AccessTokens->contains($oauth2AccessToken)) {
            $this->oauth2AccessTokens->removeElement($oauth2AccessToken);
            // set the owning side to null (unless already changed)
            if ($oauth2AccessToken->getUser() === $this) {
                $oauth2AccessToken->setUser(null);
            }
        }

        return $this;
    }

    /**
     * get created at
     *
     * @return DateTimeInterface|null
     */
    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * set created at
     *
     * @param DateTimeInterface $createdAt
     *
     * @return User
     */
    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * get updated at
     *
     * @return DateTimeInterface|null
     */
    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * set updated at
     *
     * @param DateTimeInterface $updatedAt
     * @return $this
     */
    public function setUpdatedAt(DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Triggers by doctrine lifecycle hooks to update dates
     *
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
