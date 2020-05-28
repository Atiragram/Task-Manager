<?php

declare(strict_types=1);

namespace App\Tests\Form\Model;

use App\Form\Model\UserRegistrationFormModel;
use function count;
use PHPUnit\Framework\TestCase;
use function str_repeat;
use Symfony\Component\Validator\Validation;

class UserRegistrationFormModelTest extends TestCase
{
    public function test_user_registration_model_creation(): void
    {
        $userModel = new UserRegistrationFormModel();

        self::assertNull($userModel->getUsername());
        self::assertNull($userModel->getFirstName());
        self::assertNull($userModel->getLastName());
        self::assertNull($userModel->getPassword());

        $userModel->setUsername('Username');
        $userModel->setFirstName('Firstname');
        $userModel->setLastName('Lastname');
        $userModel->setPassword('Password');

        self::assertSame('Username', $userModel->getUsername());
        self::assertSame('Firstname', $userModel->getFirstName());
        self::assertSame('Lastname', $userModel->getLastName());
        self::assertSame('Password', $userModel->getPassword());
    }

    public function test_user_registration_model_validation(): void
    {
        $userModel = new UserRegistrationFormModel();

        $validator = Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();

        $constraintViolations = $validator->validate($userModel);
        self::assertCount(4, $constraintViolations);

        $constraintViolation = $validator->validateProperty($userModel, 'username');
        self::assertSame('Username cannot be blank.', $constraintViolation->get(0)->getMessage());

        $constraintViolation = $validator->validateProperty($userModel, 'firstName');
        self::assertSame('First name cannot be blank.', $constraintViolation->get(0)->getMessage());

        $constraintViolation = $validator->validateProperty($userModel, 'lastName');
        self::assertSame('Last name cannot be blank.', $constraintViolation->get(0)->getMessage());

        $constraintViolation = $validator->validateProperty($userModel, 'password');
        self::assertSame('Password cannot be blank.', $constraintViolation->get(0)->getMessage());

        $userModel->setUsername(str_repeat('s', 65));
        $constraintViolation = $validator->validateProperty($userModel, 'username');
        self::assertSame('Username length cannot be more than 64 characters.', $constraintViolation->get(0)->getMessage());

        $userModel->setPassword(str_repeat('s', 65));
        $constraintViolation = $validator->validateProperty($userModel, 'password');
        self::assertSame('Password length cannot be more than 64 characters.', $constraintViolation->get(0)->getMessage());

        $userModel->setPassword('s');
        $constraintViolation = $validator->validateProperty($userModel, 'password');
        self::assertSame('Password length cannot be less than 5 characters.', $constraintViolation->get(0)->getMessage());

        $userModel->setUsername('s');
        $constraintViolation = $validator->validateProperty($userModel, 'username');
        self::assertSame('Username length cannot be less than 5 characters.', $constraintViolation->get(0)->getMessage());
    }
}
