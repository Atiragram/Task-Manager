<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\User;
use App\Form\Model\UserRegistrationFormModel;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserTest extends TestCase
{
    public function test_user_creation_from_model(): void
    {
        $username = 'user';
        $password = 'password';

        $userModel = new UserRegistrationFormModel();
        $userModel->setUsername($username);
        $userModel->setFirstName($username);
        $userModel->setLastName($username);
        $userModel->setPassword($password);

        $passwordEncoder = $this->createConfiguredMock(UserPasswordEncoderInterface::class, [
            'encodePassword' => $password,
        ]);

        $user = User::createFromModel($userModel, $passwordEncoder);

        self::assertSame($username, $user->getUsername());
        self::assertSame($password, $user->getPassword());
        self::assertSame(['ROLE_USER'], $user->getRoles());
    }
}
