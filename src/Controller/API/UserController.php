<?php

namespace App\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Service\CommentAnswerManager;

#[Route('')]
class UserController extends AbstractController {
    public function __construct(ManagerRegistry $em) {
        $this->em = $em;
    }

    /**
     * Return all users
     * 
     * @param UserRepository $userRepository
     */
    #[Route('/api/users', name: 'api_users', methods: ['GET'])]
    public function getUsers(UserRepository $userRepository)
    {
        $users = $userRepository->findAll();

        return $this->json($users, 200);
    }

    /**
     * Return a user
     * 
     * @param User $user
     */
    #[Route('/api/users/{id}', name: 'api_get_user', methods: ['GET'])]
    public function getOneUser(User $user)
    {
        if (!$user) {
            return $this->json('No user found for id' . $user->getId(), 404);
        }

        return $this->json($user, 200);
    }

    /** 
     * Create User
     * @param UserPasswordHasherInterface $passwordHasher
     * @param Request $request
     * @param use CommentAnswerManager $cam

    */
    #[Route('/users', name: 'add_user', methods: ['POST'])]
    public function createUser(Request $request, UserPasswordHasherInterface $passwordHasher, CommentAnswerManager $cam)
    {
        $user = new User();

        if (is_null($request->request->get('email'))) {
            return $this->json('email value can\'t be null', 400);
        }

        if (is_null($request->request->get('password'))) {
            return $this->json('Password value can\'t be null', 400);
        }

        if (is_null($request->request->get('first_name'))) {
            return $this->json('Firstname value can\'t be null', 400);
        }

        if (is_null($request->request->get('last_name'))) {
            return $this->json('Lastname value can\'t be null', 400);
        }

        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $request->request->get('password')
        );

        $user->SetEmail($request->request->get('email'));
        $user->SetFirstName($request->request->get('first_name'));
        $user->SetLastName($request->request->get('last_name'));
        $user->setRoles(['ROLE_USER']);
        $user->SetPassword($hashedPassword);

        $cam->persist($user);

        return $this->json('User created successfully', 201);

    }

    /** 
     * Edit a User
     * 
     * @param Request $request
     * @param UserPasswordHasherInterface $passwordHasher
     * @param User $user
     * @param CommentAnswerManager $cam
    */
    #[Route('/api/users/{id}', name: 'edit_user', methods: ['PUT'])]
    public function editUser(Request $request, UserPasswordHasherInterface $passwordHasher, User $user, CommentAnswerManager $cam)
    {
        if (!$user) {
            return $this->json('No User found', 404);
        }

        if (!is_null($request->request->get('email'))) {
            $user->SetEmail($request->request->get('email'));
        }

        if (!is_null($request->request->get('password'))) {
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $request->request->get('password')
            );
            $user->SetPassword($hashedPassword);
        }

        $cam->update();

        return $this->json('Updated user successfully', 200);
    }    

    /** 
     * Delete user
     * 
     * @param User $user
     * @param CommentAnswerManager $cam
    */
    #[Route('/api/users/{id}', name: 'delete_user', methods: ['DELETE'])]
    public function deleteUser(User $user, CommentAnswerManager $cam)
    {
        if (!$user) {
            return $this->json('No user found', 404);
        }

        $cam->remove($user);

        return $this->json('Deleted user successfully ', 200);
    }    
}

