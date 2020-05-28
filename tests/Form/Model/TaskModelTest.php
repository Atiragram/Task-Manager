<?php

declare(strict_types=1);

namespace App\Tests\Form\Model;

use App\Form\Model\TaskModel;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use function str_repeat;
use Symfony\Component\Validator\Validation;

class TaskModelTest extends TestCase
{
    public function test_task_model_creation(): void
    {
        $taskModel = new TaskModel();

        self::assertNull($taskModel->getTitle());
        self::assertNull($taskModel->getDescription());
        self::assertNull($taskModel->getDueDate());

        $taskModel->setTitle('Title');
        $taskModel->setDescription('Description');

        $now = new DateTimeImmutable('now');
        $taskModel->setDueDate($now);

        self::assertSame('Title', $taskModel->getTitle());
        self::assertSame('Description', $taskModel->getDescription());
        self::assertSame($now, $taskModel->getDueDate());
    }

    public function test_task_model_validation(): void
    {
        $taskModel = new TaskModel();

        $validator = Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();

        $errors = $validator->validate($taskModel);
        self::assertCount(3, $errors);

        $constraintViolation = $validator->validateProperty($taskModel, 'title');
        self::assertSame('Title cannot be blank.', $constraintViolation->get(0)->getMessage());

        $constraintViolation = $validator->validateProperty($taskModel, 'description');
        self::assertSame('Description cannot be blank.', $constraintViolation->get(0)->getMessage());

        $constraintViolation = $validator->validateProperty($taskModel, 'dueDate');
        self::assertSame('Due date cannot be empty.', $constraintViolation->get(0)->getMessage());

        $taskModel->setTitle(str_repeat('s', 129));
        $taskModel->setDescription(str_repeat('s', 256));

        $errors = $validator->validate($taskModel);
        self::assertCount(3, $errors);

        $constraintViolation = $validator->validateProperty($taskModel, 'title');
        self::assertSame('Task title cannot be more than 128 characters.', $constraintViolation->get(0)->getMessage());

        $constraintViolation = $validator->validateProperty($taskModel, 'description');
        self::assertSame('Description cannot be more than 255 characters.', $constraintViolation->get(0)->getMessage());

        $taskModel->setTitle('s');
        $taskModel->setDescription('s');

        $constraintViolation = $validator->validateProperty($taskModel, 'title');
        self::assertSame('Task title cannot be less than 2 characters.', $constraintViolation->get(0)->getMessage());

        $constraintViolation = $validator->validateProperty($taskModel, 'description');
        self::assertSame('Description cannot be less than 5 characters.', $constraintViolation->get(0)->getMessage());
    }
}
