<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use App\Form\Model\TaskModel;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    private TaskModel $taskModel;

    protected function setUp(): void
    {
        $this->taskModel = new TaskModel();
        $this->taskModel->setTitle('Title');
        $this->taskModel->setDescription('Description');
    }

    public function test_task_creation_from_model(): void
    {
        $user = $this->createMock(User::class);

        $now = new DateTimeImmutable('now');
        $this->taskModel->setDueDate($now);

        $task = Task::createFromModel($this->taskModel, $user);

        self::assertSame('Title', $task->getTitle());
        self::assertSame('Description', $task->getDescription());
        self::assertSame($now, $task->getDueDate());
        self::assertSame($user, $task->getUser());
        self::assertFalse($task->isCompleted());
        self::assertFalse($task->isDeleted());
        self::assertNull($task->getDeletedDate());
        self::assertNotNull($task->getCreatedDate());
        self::assertInstanceOf(DateTimeImmutable::class, $task->getCreatedDate());
    }
}
