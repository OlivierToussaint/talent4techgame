<?php

/*
 * This file is part of the Talent4tech project.
 * Olivier Toussaint <olivier@toussaint.fr>
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
{
    public const ALIVE = 'alive';

    public const DEAD = 'dead';

    public const ATTAQUE_COST = 10;

    public const HEAL_COST = 2;

    public const AP_REGEN = 60;

    public const ROLE_USER = 'ROLE_USER';

    public const ROLE_ADMIN = 'ROLE_ADMIN';

    public function __construct()
    {
        $this->roles = [self::ROLE_USER];
    }

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Range(
     *      min = 0,
     *      max = 100,
     *      minMessage = "You must be at least {{ limit }}cm tall to enter",
     *      maxMessage = "You cannot be taller than {{ limit }}cm to enter"
     * )
     */
    private $hp = 100;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ap = 100;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $connectAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $avatar;

    public function getState()
    {
        if ($this->hp <= 0) {
            return self::DEAD;
        }

        return self::ALIVE;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

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
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getHp(): ?int
    {
        return $this->hp;
    }

    public function setHp(?int $hp): self
    {
        $this->hp = $hp;

        return $this;
    }

    public function getAp(): ?int
    {
        return $this->ap;
    }

    public function setAp(?int $ap): self
    {
        $this->ap = $ap;

        return $this;
    }

    public function getConnectAt(): ?\DateTimeInterface
    {
        return $this->connectAt;
    }

    public function setConnectAt(?\DateTimeInterface $connectAt): self
    {
        $this->connectAt = $connectAt;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }
}
