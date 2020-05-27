<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Task;
use App\Form\Model\TaskModel;
use App\Form\Type\TaskType;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @SWG\Tag(name="Tasks")
 */
class TaskController extends AbstractController
{
    /**
     * List the tasks of the specified user.
     *
     * @Route("/tasks", methods={"GET"})
     * @Security(name="Bearer")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the tasks of a user",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Task::class, groups={"list"}))
     *     )
     * )
     *
     * @SWG\Parameter(
     *     name="dueDate",
     *     in="query",
     *     type="string",
     *     format="Y-m-d",
     *     description="The field used to get tasks for a specific date. Today by default.",
     *     required=false,
     *     @SWG\Schema(
     *         type="string",
     *     )
     * )
     *
     * @SWG\Parameter(
     *     name="title",
     *     in="query",
     *     type="string",
     *     description="The field used to filter tasks with a specific title.",
     *     required=false,
     *     @SWG\Schema(
     *         type="string",
     *     )
     * )
     */
    public function list(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer)
    {
        $dueDate = new DateTimeImmutable('today');

        if ($request->get('dueDate')) {
            $dueDate = DateTimeImmutable::createFromFormat('Y-m-d', $request->get('dueDate'));
        }

        if ($dueDate) {
            $title = $request->get('title', null);

            $tasks = $entityManager
                ->getRepository(Task::class)
                ->findForUser($this->getUser(), $dueDate, $title);
        }

        return $this->json(
            $serializer->serialize(
                $tasks ?? [],
                'json',
                SerializationContext::create()->setGroups(['list'])
            ),
            JsonResponse::HTTP_OK
        );
    }

    /**
     * Creates a new task for a user.
     *
     * @Route("/tasks", name="task_create", methods={Request::METHOD_POST})
     * @Security(name="Bearer")
     *
     * @SWG\Parameter(
     *     name="title",
     *     in="formData",
     *     type="string",
     *     description="Task title",
     *     required=true
     * )
     *
     * @SWG\Parameter(
     *     name="description",
     *     in="formData",
     *     type="string",
     *     description="Task description",
     *     required=true
     * )
     *
     * @SWG\Parameter(
     *     name="dueDate",
     *     in="formData",
     *     type="string",
     *     description="Task due date",
     *     format="Y-m-d H:i",
     *     required=true
     * )
     *
     * @SWG\Response(
     *     response=Response::HTTP_CREATED,
     *     description="Returns if task was successfully created"
     * )
    @SWG\Response(
     *     response=Response::HTTP_BAD_REQUEST,
     *     description="Returns if task data is not valid"
     * )
     */
    public function create(
        Request $request,
        EntityManagerInterface $entityManager
    ) {
        $taskModel = new TaskModel();
        $form = $this->createForm(TaskType::class, $taskModel);
        $form->submit($request->request->all());

        if (!$form->isSubmitted() || !$form->isValid()) {
            foreach ($form->getErrors(true) as $error) {
                $errors[] = $error->getMessage();
            }

            return JsonResponse::create(
                $errors ?? [],
                Response::HTTP_BAD_REQUEST
            );
        }

        $task = Task::createFromModel($taskModel, $this->getUser());

        $entityManager->persist($task);
        $entityManager->flush();

        return JsonResponse::create(
            null,
            Response::HTTP_CREATED
        );
    }

    /**
     * Deletes a task.
     *
     * @Route("/tasks/{id}", name="task_delete", methods={Request::METHOD_DELETE})
     * @Security(name="Bearer")
     *
     * @SWG\Response(
     *     response=Response::HTTP_NO_CONTENT,
     *     description="Returns if task was successfully deleted"
     * )
     */
    public function delete(
        int $id,
        EntityManagerInterface $entityManager
    ) {
        /** @var Task $task */
        $task = $entityManager->getRepository(Task::class)
            ->find($id);

        if ($task) {
            $task->delete();
            $entityManager->flush();
        }

        return JsonResponse::create(
            null,
            Response::HTTP_NO_CONTENT
        );
    }
}
