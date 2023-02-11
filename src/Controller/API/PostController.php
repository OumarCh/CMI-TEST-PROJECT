<?php

namespace App\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PostRepository;
use App\Entity\Post;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Doctrine\Persistence\ManagerRegistry;
use App\Service\CommentAnswerManager;

#[Route('/api', name: 'api_')]
class PostController extends AbstractController {
    private $encoders;
    private $normalizers;
    private $em;
    
    public function __construct(ManagerRegistry $em) {
        $this->encoders = [new JsonEncoder()];
        $this->normalizers = [new ObjectNormalizer()];
        $this->em = $em;
    }

    /**
     * Return all posts
     * 
     * @param PostRepository $postRepo
     */
    #[Route('/posts', name: 'get_posts', methods: ['GET'])]
    public function getPosts(PostRepository $postRepo)
    {
        $posts = $postRepo->AllPosts();
        $serializer = new Serializer($this->normalizers, $this->encoders);
        $jsonContent = $serializer->serialize($posts, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);       
        $response = new Response($jsonContent, 200);
        
        $response->headers->set('Content-Type', 'application/json');
        
        return $response;
    }

    /**
     * Return a post
     * 
     * @param Post $post
     */
    #[Route('/posts/{id}', name: 'get_post', methods: ['GET'])]
    public function getPost(Post $post)
    {
        if (!$post) {
            return $this->json('No post found', 404);
        }

        $serializer = new Serializer($this->normalizers, $this->encoders);
        $jsonContent = $serializer->serialize($post, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);       
        $response = new Response($jsonContent, 200);
        
        $response->headers->set('Content-Type', 'application/json');
        
        return $response;

        return $this->json($post, 200);
    }

    /** 
     * Create post
     * 
     * @param Request $request
     * @param CommentAnswerManager $cam
    */
    #[Route('/posts', name: 'add_post', methods: ['POST'])]
    public function createPost(Request $request, CommentAnswerManager $cam)
    {
        $post = new Post();

        if (is_null($request->request->get('title'))) {
            return $this->json('Title value can\'t be null', 400);
        }

        if (is_null($request->request->get('content'))) {
            return $this->json('Content value can\'t be null', 400);
        }
        
        $post->SetTitle($request->request->get('title'));
        $post->SetContent($request->request->get('content'));

        $cam->persist($post);

        return $this->json('Post created successfully with', 201);
    }

    /** 
     * Edit a post
     * 
     * @param Request $request
     * @param Post $post
     * @param CommentAnswerManager $cam
    */
    #[Route('/posts/{id}', name: 'edit_post', methods: ['PUT'])]
    public function editPost(Request $request, Post $post, CommentAnswerManager $cam)
    {
        if (!$post) {
            return $this->json('No post found for id' . $post->getId(), 404);
        }

        if (!is_null($request->request->get('title'))) {
            $post->SetTitle($request->request->get('title'));
        }

        if (!is_null($request->request->get('content'))) {
            $post->SetContent($request->request->get('content'));
        }

        $cam->update();

        return $this->json('Updated post successfully', 200);
    }    

    /** 
     * Delete post 
     * 
     * @param Post $post
     * @param CommentAnswerManager $cam
    */
    #[Route('/posts/{id}', name: 'delete_post', methods: ['DELETE'])]
    public function deletePost(Post $post, CommentAnswerManager $cam)
    {
        if (!$post) {
            return $this->json('No post found', 404);
        }

        $cam->remove($post);

        return $this->json('Deleted post successfully', 200);
    }    
}

