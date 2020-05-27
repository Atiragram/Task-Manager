<?php

declare(strict_types=1);

namespace App\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

class UserRegistrationFormModel
{
    /**
     * @Assert\NotBlank(message="Username cannot be blank.")
     * @Assert\Length(
     *     min="5",
     *     max="64",
     *     minMessage="Username length cannot be less than {{ limit }}.",
     *     maxMessage="Username length cannot be more than {{ limit }}.",
     * )
     */
    private ?string $username = null;

    /**
     * @Assert\NotBlank(message="First name cannot be blank.")
     * @Assert\Length(
     *     min="5",
     *     max="64",
     *     minMessage="Fisrt name length cannot be less than {{ limit }}.",
     *     maxMessage="First name length cannot be more than {{ limit }}.",
     * )
     */
    private ?string $firstName = null;

    /**
     * @Assert\NotBlank(message="Last name cannot be blank.")
     * @Assert\Length(
     *     min="5",
     *     max="64",
     *     minMessage="Last name length cannot be less than {{ limit }}.",
     *     maxMessage="Last name length cannot be more than {{ limit }}.",
     * )
     */
    private ?string $lastName = null;

    /**
     * @Assert\Length(
     *     min=5,
     *     max=64,
     *     minMessage="Password length cannot be less than {{ limit }}.",
     *     maxMessage="Password length cannot be more than {{ limit }}.",
     * )
     * @Assert\NotBlank(message="Password cannot be blank.")
     */
    private ?string $password = null;

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): void
    {
        $this->username = $username;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }
}
