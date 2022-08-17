<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type: "string", length: 255)]
    private ?string $username;

    #[ORM\Column(type: "string", length: 255)]
    private string $fullName;

    #[ORM\Column(type: "datetime", nullable: true)]
    private ?DateTimeInterface $lastLoggedInAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @inheritdoc
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): User
    {
        $this->username = $username;
        return $this;
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): User
    {
        $this->fullName = $fullName;
        return $this;
    }

    public function getLastLoggedInAt(): ?DateTimeInterface
    {
        return $this->lastLoggedInAt;
    }

    public function setLastLoggedInAt(?DateTimeInterface $lastLoggedInAt): self
    {
        $this->lastLoggedInAt = $lastLoggedInAt;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    /**
     * @inheritdoc
     */
    public function getPassword(): ?string
    {
        return $this->username;
    }

    /**
     * @inheritdoc
     */
    public function getSalt(): string
    {
        return '';
    }

    /**
     * @inheritdoc
     */
    public function eraseCredentials()
    {
        // do nothing
    }
}
