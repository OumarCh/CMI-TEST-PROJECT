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
use App\Entity\Note;
use App\Form\CommentType;
use App\Form\AnswerType;
use App\Form\NoteType;

#[Route('/', name: 'app_')]
class PagesController extends AbstractController
{
    /**
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @param PostRepository $postRepository
     * @param CommentRepository $postRepository
     */
    #[Route('', name: 'home', methods: ['GET'])]
    public function index(Request $request, PaginatorInterface $paginator, PostRepository $postRepository, CommentRepository $commentRepository)
    {
        $lastComments = $commentRepository->lastComments(); 
        $posts = $paginator->paginate(
            $postRepository->AllPosts(), // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            20
        );

        return $this->render('home/index.html.twig', [
            'last_comments' => $lastComments,
            'posts' => $posts
        ]);

    }

    /** 
     * Show and publish comment on post
     * 
     * @param Request $request
     * @param Post $post
    */
    #[Route('post/{id}', name: 'post', methods: ['GET', 'POST'])]
    public function showPost(string $id, Request $request, Post $post, CommentAnswerManager $cam)
    {
        $newComment = new Comment();
        $formComment = $this->createForm(CommentType::class, $newComment, array(
            'action' => $this->generateUrl('app_post', array('id' => $post->getId())),
            'method' => 'POST',
        ));

        $formComment->handleRequest($request);
        if ($formComment->isSubmitted() && $formComment->isValid()) {
            $comment = $formComment->getData();

            $cam->addComment($comment, $post, $this->getUser());
            $this->addFlash('notice', 'Merci ! Votre commentaire a été ajouté.');

            return $this->redirectToRoute('app_post', ['id' => $post->getId()]);
        }

        return $this->render('post/index.html.twig', [
            'post' => $post,
            'form_comment' => $formComment->createView(),
        ]);
    }

    /** 
     *  Show and publish answer on comment
     * 
     * @param Request $request
     * @param Comment $comment
    */
    #[Route('comment/{id}', name: 'comment', methods: ['GET', 'POST'])]
    public function showComment(Request $request, Comment $comment, CommentAnswerManager $cam)
    {
        $newAnswer = new Answer();
        $formAnswer = $this->createForm(AnswerType::class, $newAnswer, array(
            'action' => $this->generateUrl('app_comment', array('id' => $comment->getId())),
            'method' => 'POST',
        ));

        $formAnswer->handleRequest($request);
        if ($formAnswer->isSubmitted() && $formAnswer->isValid()) {
            $answer = $formAnswer->getData();

            $cam->addAnswer($answer, $comment, $this->getUser());
            $this->addFlash('notice', 'Merci ! Votre réponse a été ajoutée.');

            return $this->redirectToRoute('app_post', ['id' => $comment->getPost()->getId()]);
        }

        return $this->render('comment/index.html.twig', [
            'comment' => $comment,
            'form_answer' => $formAnswer->createView()
        ]);
    }

    /** 
     * Show and publish note on comment
     * 
     * @param Request $request
     * @param Comment $comment
    */
    #[Route('note/comment/{id}', name: 'note', methods: ['GET', 'POST'])]
    public function actionNote(Request $request, Comment $comment, CommentAnswerManager $cam)
    {
        $note = new Note();
        $formNote = $this->createForm(NoteType::class, $note, array(
            'action' => $this->generateUrl('app_note', array('id' => $comment->getId())),
            'method' => 'POST',
        ));

        $formNote->handleRequest($request);
        if ($formNote->isSubmitted() && $formNote->isValid()) {
            $note = $formNote->getData();
            $note->setUser($this->getUser());
            $note->setComment($comment);

            $cam->persist($note);
            $this->addFlash('notice', 'Merci ! Votre note a été bien prise en compte.');

            return $this->redirectToRoute('app_post', ['id' => $comment->getPost()->getId()]);
        }

        return $this->render('note/index.html.twig', [
            'comment' => $comment,
            'form_note' => $formNote->createView()
        ]);
    }

    #[Route('logout', name: 'logout')]
    public function logout()
    {}
}