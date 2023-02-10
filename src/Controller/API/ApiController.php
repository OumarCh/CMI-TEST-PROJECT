<?php

namespace App\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\PostRepository;
use App\Repository\CommentRepository;
use App\Entity\Post;
use App\Entity\Comment;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use App\Repository\ArticleRepository;

#[Route('/api', name: 'api_')]
class ApiController extends AbstractController {
    #[Route('/posts', name: 'get_posts', methods: ['GET'])]
    public function getPosts(PostRepository $postRepo)
    {
        // On récupère la liste des articles
        $posts = $postRepo->findAll();

        $encoders = [new JsonEncoder()];

        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);

        $jsonContent = $serializer->serialize($posts, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);

        $response = new Response($jsonContent);

        // On ajoute l'entête HTTP
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    #[Route('/post/{$id}', name: 'get_post', methods: ['GET'])]
    public function getPost(Post $post)
    {
        $encoders = [new JsonEncoder()];

        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);

        $jsonContent = $serializer->serialize($post, 'json');

        $response = new Response($jsonContent);

        // On ajoute l'entête HTTP
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /** 
     * Create comment
     * @param Request $request
     * @param PostRepository $post
    */
    #[Route('/post', name: 'add_post', methods: ['POST'])]
    public function createPost(Request $request)
    {
        return;
    }

    /** 
     * Create answer
     * @param Request $request
     * @param PostRepository $post
    */
    #[Route('/post/{id}', name: 'post_answer', methods: ['POST'])]
    public function deletePost(Post $post)
    {
        return;
    }    
}

