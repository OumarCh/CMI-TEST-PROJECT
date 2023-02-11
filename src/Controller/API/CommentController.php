<?php

namespace App\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PostRepository;
use App\Repository\CommentRepository;
use App\Entity\Post;
use App\Entity\Comment;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Doctrine\Persistence\ManagerRegistry;
use App\Service\CommentAnswerManager;

#[Route('/api', name: 'api_')]
class CommentController extends AbstractController {
    private $encoders;
    private $normalizers;
    private $em;

    public function __construct(ManagerRegistry $em) {
        $this->encoders = [new JsonEncoder()];
        $this->normalizers = [new ObjectNormalizer()];
        $this->em = $em;
    }

    /**
     * Return all comments
     * 
     * @param CommentRepository $commentRepo
     */
    #[Route('/comments', name: 'get_comments', methods: ['GET'])]
    public function getComments(CommentRepository $commentRepo)
    {
        $comments = $commentRepo->findAll();
        $serializer = new Serializer($this->normalizers, $this->encoders);

        $jsonContent = $serializer->serialize($comments, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);       
        $response = new Response($jsonContent, 200);
        
        $response->headers->set('Content-Type', 'application/json');
        
        return $response;
    }

    /**
     * Return a comment
     * 
     * @param Comment $comment
     */
    #[Route('/comments/{id}', name: 'get_comment', methods: ['GET'])]
    public function getComment(Comment $comment)
    {
        if (!$comment) {
            return $this->json('No comment found', 404);
        }

        $serializer = new Serializer($this->normalizers, $this->encoders);

        $jsonContent = $serializer->serialize($comment, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);      

        $response = new Response($jsonContent, 200);
        
        $response->headers->set('Content-Type', 'application/json');
        
        return $response;        

        return $this->json($comment, 200);
    }

    /** 
     * Create comment
     * 
     * @param Request $request
     * @param PostRepository $postRepository
     * @param CommentAnswerManager $cam
    */
    #[Route('/comments', name: 'add_comment', methods: ['POST'])]
    public function createComment(Request $request, PostRepository $postRepository, CommentAnswerManager $cam)
    {
        $comment = new Comment();
        $post = $postRepository->find((int)$request->request->get('post_id'));

        if (is_null($post)) {
            return $this->json('Post value can\'t be null', 400);
        }

        if (is_null($request->request->get('content'))) {
            return $this->json('Content value can\'t be null', 400);
        }

        $comment->SetContent($request->request->get('content'));
        $comment->SetPost($post);

        $cam->persist($comment);

        return $this->json('Comment created successfully', 201);
    }

    /** 
     * Edit a comment
     * 
     * @param Request $request
     * @param Comment $comment
     * @param CommentAnswerManager $cam
    */
    #[Route('/comments/{id}', name: 'edit_comment', methods: ['PUT'])]
    public function editComment(Request $request, Comment $comment, CommentAnswerManager $cam)
    {
        if (!$comment) {
            return $this->json('No comment found for id' . $comment->getId(), 404);
        }

        if (!is_null($request->request->get('content'))) {
            $comment->SetContent($request->request->get('content'));
        }

        $cam->update();

        return $this->json('Updated comment successfully', 200);
    }    

    /** 
     * Delete comment
     * 
     * @param Comment $comment
     * @param CommentAnswerManager $cam
    */
    #[Route('/comments/{id}', name: 'delete_comment', methods: ['DELETE'])]
    public function deleteComment(Comment $comment, CommentAnswerManager $cam)
    {
        if (!$comment) {
            return $this->json('No comment found', 404);
        }

        $cam->remove($comment);

        return $this->json('Deleted comment successfully', 200);
    }    
}

