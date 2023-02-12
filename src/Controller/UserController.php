<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Service\CommentAnswerManager;
use Symfony\Component\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[Route('/', name: 'app_')]
class UserController extends AbstractController {
    public function __construct(ManagerRegistry $em) {
        $this->em = $em;
    }

    /** 
     * Create User
     *  
     * @param UserPasswordHasherInterface $passwordHasher
     * @param Request $request
     * @param use CommentAnswerManager $cam
    */
    #[Route('users', name: 'add_user', methods: ['POST'])]
    public function createUser(Request $request, UserPasswordHasherInterface $passwordHasher, CommentAnswerManager $cam)
    {
        $user = new User();
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

        return $this->redirectToRoute('app_home');
    }

    /** 
     * Edit a User
     * 
     * @IsGranted("ROLE_USER")
     * 
     * @param Request $request
     * @param UserPasswordHasherInterface $passwordHasher
     * @param User $user
     * @param CommentAnswerManager $cam
    */
    #[Route('users/{id}', name: 'edit_user', methods: ['PUT'])]
    public function editUser(Request $request, UserPasswordHasherInterface $passwordHasher, User $user, CommentAnswerManager $cam)
    {
        if (!$user) {
            throw new NotFoundHttpException();
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

        return $this->redirectToRoute('app_home');
    }    

    /** 
     * Delete user
     * 
     * @IsGranted("ROLE_USER")
     * 
     * @param User $user
     * @param CommentAnswerManager $cam
    */
    #[Route('users/{id}', name: 'delete_user', methods: ['DELETE'])]
    public function deleteUser(User $user, CommentAnswerManager $cam)
    {
        if (!$user) {
            throw new NotFoundHttpException();
        }

        $cam->remove($user);

        return $this->redirectToRoute('app_home');
    }    
}

