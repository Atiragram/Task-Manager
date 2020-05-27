<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\Model\UserRegistrationFormModel;
use App\Form\Type\UserRegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @SWG\Tag(name="User")
 */
class UserController extends AbstractController
{
    /**
     * Creates a new user with specified username and password.
     *
     * @Route("/users", name="user_create", methods={Request::METHOD_POST})
     *
     * @SWG\Parameter(
     *     name="firstName",
     *     in="formData",
     *     type="string",
     *     description="First name",
     *     required=true
     * )
     *
     * @SWG\Parameter(
     *     name="lastName",
     *     in="formData",
     *     type="string",
     *     description="Last name",
     *     required=true
     * )
     *
     * @SWG\Parameter(
     *     name="username",
     *     in="formData",
     *     type="string",
     *     description="Username",
     *     required=true
     * )
     *
     * @SWG\Parameter(
     *     name="password",
     *     in="formData",
     *     type="string",
     *     description="Password",
     *     required=true
     * )
     *
     * @SWG\Response(
     *     response=Response::HTTP_CREATED,
     *     description="Returns if user was successfully created"
     * )
    @SWG\Response(
     *     response=Response::HTTP_BAD_REQUEST,
     *     description="Returns if user data is not valid"
     * )
     */
    public function create(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $userRegistrationModel = new UserRegistrationFormModel();
        $form = $this->createForm(UserRegistrationType::class, $userRegistrationModel);
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

        $user = User::createFromModel($userRegistrationModel, $passwordEncoder);

        $entityManager->persist($user);
        $entityManager->flush();

        return JsonResponse::create(
            null,
            Response::HTTP_CREATED
        );
    }
}
