<?php

declare(strict_types=1);

namespace App\Entity;

use App\Form\Model\UserRegistrationFormModel;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Table(
 *     schema="Tasks",
 *     name="user",
 *     options={"comment"="A user"},
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="UK_usename", columns={"username"}),
 *     }
 * )
 *
 * @ORM\Entity(
 *     repositoryClass="Doctrine\ORM\EntityRepository"
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Column(name="user_id", type="smallint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private ?int $id;

    /**
     * @ORM\Column(name="first_name", type="string", length=64)
     */
    private string $firstName;

    /**
     * @ORM\Column(name="last_name", type="string", length=64)
     */
    private string $lastName;

    /**
     * @ORM\Column(name="username", type="string", length=64)
     */
    private string $username;

    /**
     * @ORM\Column(name="password", type="string", length=64)
     */
    private string $password;

    /**
     * @ORM\Column(name="roles", type="json_array")
     */
    private array $roles;

    public function __construct(
        string $firstName,
        string $lastName,
        string $username
    ) {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->username = $username;
    }

    public static function createFromModel(
        UserRegistrationFormModel $registrationFormModel,
        UserPasswordEncoderInterface $passwordEncoder
    ): self {
        $self = new self(
            $registrationFormModel->getFirstName(),
            $registrationFormModel->getLastName(),
            $registrationFormModel->getUsername(),
        );

        $self->roles = ['ROLE_USER'];
        $self->password = $passwordEncoder->encodePassword($self, $registrationFormModel->getPassword());

        return $self;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getSalt()
    {
        return null;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function eraseCredentials()
    {
    }
}
