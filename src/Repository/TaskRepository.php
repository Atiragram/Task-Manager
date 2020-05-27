<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Task;
use App\Entity\User;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityRepository;
use function sprintf;

class TaskRepository extends EntityRepository
{
    /**
     * @param User $user
     * @param DateTime $date
     *
     * @return Task[]
     */
    public function findForUser(User $user, DateTimeImmutable $date, ?string $title = null): array
    {
        $qb = $this->createQueryBuilder('task');

        $qb
            ->andWhere($qb->expr()->eq('task.user', ':user'))
            ->andWhere($qb->expr()->gte('task.dueDate', ':fromDate'))
            ->andWhere($qb->expr()->lte('task.dueDate', ':toDate'))
            ->andWhere($qb->expr()->lte('task.isDeleted', ':isDeleted'))
            ->orderBy('task.dueDate');

        $queryParameters = [
            'user' => $user,
            'isDeleted' => false,
            'fromDate' => $date->setTime(0, 0, 0)->format('Y-m-d H:i:s'),
            'toDate' => $date->setTime(23, 59, 59)->format('Y-m-d H:i:s'),
        ];

        if ($title) {
            $qb->andWhere($qb->expr()->like('task.title', ':title'));
            $queryParameters['title'] = sprintf('%%%s%%', $title);
        }

        return $qb
            ->setParameters($queryParameters)
            ->getQuery()
            ->getResult();
    }
}
