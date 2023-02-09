<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\PostRepository;
use App\Repository\CommentRepository;
use App\Entity\Post;
use App\Entity\Comment;

#[Route('/', name: 'app_')]
class NavigationController extends AbstractController
{
    /**
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @param PostRepository $postRepository
     * @param CommentRepository $postRepository
     */
    #[Route('', name: 'home', methods: ['GET', 'HEAD'])]
    public function index(Request $request, PaginatorInterface $paginator, PostRepository $postRepository, CommentRepository $commentRepository)
    {
        $lastComments = $commentRepository->lastComments(); 
        $posts = $paginator->paginate(
            $postRepository->findAll(), // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            20
        );

        return $this->render('home/index.html.twig', [
            'last_comments' => $lastComments,
            'posts' => $posts
        ]);

    }

    /** 
     * @param Post $post
    */
    #[Route('post/{id}', name: 'post', methods: ['GET', 'HEAD'])]
    public function showPost(Post $post)
    {
        return $this->render('post/index.html.twig', [
            'post' => $post
        ]);
    }
}

