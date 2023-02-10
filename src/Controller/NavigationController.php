<?php

namespace App\Controller;

use App\Service\CommentAnswerManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\PostRepository;
use App\Repository\CommentRepository;
use App\Entity\Post;
use App\Entity\Comment;
use App\Entity\Answer;
use App\Form\CommentType;
use App\Form\AnswerType;

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
     * Show post and publish comment on post
     * @param string $id
     * @param Request $request
     * @param Post $post
    */
    #[Route('post/{id}', name: 'post', methods: ['GET', 'POST', 'HEAD'])]
    public function showPost(string $id, Request $request, Post $post, CommentAnswerManager $cam)
    {
        $newComment = new Comment();
        $formComment = $this->createForm(CommentType::class, $newComment, array(
            'action' => $this->generateUrl('app_post', array('id' => $id)),
            'method' => 'POST',
        ));

        $formComment->handleRequest($request);
        if ($formComment->isSubmitted() && $formComment->isValid()) {
            $comment = $formComment->getData();

            $cam->addComment($comment, $post);

            return $this->redirectToRoute('app_post', ['id' => $post->getId()]);
        }

        return $this->render('post/index.html.twig', [
            'post' => $post,
            'form_comment' => $formComment->createView(),
        ]);
    }

     /** 
     * @param string $id
     * @param Request $request
     * @param Comment $comment
    */
    #[Route('comment/{id}', name: 'comment', methods: ['GET', 'POST', 'HEAD'])]
    public function showComment(string $id, Request $request, Comment $comment, CommentAnswerManager $cam)
    {
        $newAnswer = new Answer();
        $formAnswer = $this->createForm(AnswerType::class, $newAnswer, array(
            'action' => $this->generateUrl('app_comment', array('id' => $id)),
            'method' => 'POST',
        ));

        $formAnswer->handleRequest($request);
        if ($formAnswer->isSubmitted() && $formAnswer->isValid()) {
            $answer = $formAnswer->getData();

            $cam->addAnswer($answer, $comment);

            return $this->redirectToRoute('app_post', ['id' => $comment->getPost()->getId()]);
        }

        return $this->render('comment/index.html.twig', [
            'comment' => $comment,
            'form_answer' => $formAnswer->createView()
        ]);
    }

    #[Route('logout', name: 'logout')]
    public function logout()
    {}
}