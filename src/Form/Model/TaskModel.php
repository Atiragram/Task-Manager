<?php

declare(strict_types=1);

namespace App\Form\Model;

use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Assert;

class TaskModel
{
    /**
     * @Assert\NotBlank(message="Title cannot be blank.")
     * @Assert\Length(
     *     min="5",
     *     max="64",
     *     minMessage="Task title cannot be less than {{ limit }} characters.",
     *     maxMessage="Task title cannot be more than {{ limit }} characters.",
     * )
     */
    private ?string $title = null;

    /**
     * @Assert\NotBlank(message="First name cannot be blank.")
     * @Assert\Length(
     *     min="5",
     *     max="64",
     *     minMessage="Description cannot be less than {{ limit }} characters.",
     *     maxMessage="Description cannot be more than {{ limit }} characters.",
     * )
     */
    private ?string $description = null;

    /**
     * @Assert\NotNull(message="Due date cannot be empty.")
     */
    private ?DateTimeImmutable $dueDate = null;

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getDueDate(): ?DateTimeImmutable
    {
        return $this->dueDate;
    }

    public function setDueDate(?DateTimeImmutable $dueDate): void
    {
        $this->dueDate = $dueDate;
    }
}
