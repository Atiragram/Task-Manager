<?php

declare(strict_types=1);

namespace App\Entity;

use App\Form\Model\TaskModel;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Table(
 *     schema="Tasks",
 *     name="task",
 *     options={"comment"="A task for a user."},
 *     indexes={
 *          @ORM\Index(name="K_user_id", columns={"user_id"}),
 * })
 *
 * @ORM\Entity(
 *     repositoryClass="App\Repository\TaskRepository"
 * )
 */
class Task
{
    /**
     * @ORM\Column(name="task_id", type="smallint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @JMS\Groups({"list"})
     */
    private ?int $id;

    /**
     * @ORM\Column(name="title", type="string", length=128)
     *
     * @JMS\Groups({"list"})
     */
    private string $title;

    /**
     * @ORM\Column(name="description", type="string", length=255)
     *
     * @JMS\Groups({"list"})
     */
    private string $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="user_id", nullable=false)
     */
    private User $user;

    /**
     * @ORM\Column(name="due_date", type="datetime")
     *
     * @JMS\Groups({"list"})
     */
    private DateTimeImmutable $dueDate;

    /**
     * @ORM\Column(name="created_date", type="datetime")
     *
     * @JMS\Groups({"list"})
     */
    private DateTimeImmutable $createdDate;

    /**
     * @ORM\Column(name="deleted_date", type="datetime", nullable=true)
     */
    private ?DateTimeImmutable $deletedDate = null;

    /**
     * @ORM\Column(name="is_deleted", type="boolean")
     */
    private bool $isDeleted = false;

    /**
     * @ORM\Column(name="is_completed", type="boolean")
     *
     * @JMS\Groups({"list"})
     */
    private bool $isCompleted = false;

    public function __construct(
        string $title,
        string $description,
        DateTimeImmutable $dueDate,
        User $user
    ) {
        $this->title = $title;
        $this->description = $description;
        $this->dueDate = $dueDate;
        $this->user = $user;
        $this->createdDate = new DateTimeImmutable();
    }

    public static function createFromModel(TaskModel $taskModel, User $user): self
    {
        return new self(
            $taskModel->getTitle(),
            $taskModel->getDescription(),
            $taskModel->getDueDate(),
            $user
        );
    }

    public function delete()
    {
        $this->isDeleted = true;
        $this->deletedDate = new DateTimeImmutable();
    }
}
