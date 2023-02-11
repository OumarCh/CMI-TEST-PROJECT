<?php

namespace App\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AnswerRepository;
use App\Repository\CommentRepository;
use App\Entity\Answer;
use App\Entity\Comment;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Doctrine\Persistence\ManagerRegistry;
use App\Service\CommentAnswerManager;

#[Route('/api', name: 'api_')]
class AnswerController extends AbstractController {
    private $encoders;
    private $normalizers;
    private $em;

    public function __construct(ManagerRegistry $em) 
    {
        $this->encoders = [new JsonEncoder()];
        $this->normalizers = [new ObjectNormalizer()];
        $this->em = $em;
    }

    /**
     * Return all answers
     * 
     * @param AnswerRepository $answerRepository
     */
    #[Route('/answers', name: 'get_answers', methods: ['GET'])]
    public function getAnswers(AnswerRepository $answerRepository)
    {
        $answers = $answerRepository->findAll();
        $serializer = new Serializer($this->normalizers, $this->encoders);
        $jsonContent = $serializer->serialize($answers, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);       
        $response = new Response($jsonContent, 200);
        
        $response->headers->set('Content-Type', 'application/json');
        
        return $response;
    }

    /** 
     * Create answer
     * 
     * @param Request $request
     * @param CommentRepository $commentRepository
     * @param CommentAnswerManager $cam
    */
    #[Route('/answers', name: 'add_answer', methods: ['POST'])]
    public function createAnswer(Request $request, CommentRepository $commentRepository, CommentAnswerManager $cam)
    {
        $answer = new Answer();
        $comment = $commentRepository->find((int)$request->request->get('comment_id'));

        if (is_null($comment)) {
            return $this->json('Comment value can\'t be null', 400);
        }

        if (is_null($request->request->get('content'))) {
            return $this->json('Content value can\'t be null', 400);
        }

        $answer->SetContent($request->request->get('content'));
        $answer->SetComment($comment);
        $cam->persist($answer);

        return $this->json('Answer created successfully', 201);
    }

    /**
     * Return a answer
     * 
     * @param Answer $answer
     */
    #[Route('/answers/{id}', name: 'get_answer', methods: ['GET'])]
    public function getAnswer(Answer $answer)
    {
        if (!$answer) {
            return $this->json('No answer found', 404);
        }

        $serializer = new Serializer($this->normalizers, $this->encoders);
        $jsonContent = $serializer->serialize($answer, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);       
        $response = new Response($jsonContent, 200);
        
        $response->headers->set('Content-Type', 'application/json');
        
        return $response;

        return $this->json($answer, 200);
    }

    /** 
     * Edit a answer
     * 
     * @param Request $request
     * @param Answer $answer
     * @param CommentAnswerManager $cam
    */
    #[Route('/answers/{id}', name: 'edit_answer', methods: ['PUT'])]
    public function editAnswer(Request $request, Answer $answer, CommentAnswerManager $cam)
    {
        if (!$answer) {
            return $this->json('No answer found for id' . $answer->getId(), 404);
        }

        $answer->SetContent($request->request->get('content'));

        $cam->update();

        return $this->json('Updated answer successfully with id ' . $answer->getId(), 200);
    }    

    /** 
     * Delete answer
     * 
     * @param Answer $answer
     * @param CommentAnswerManager $cam
    */
    #[Route('/answers/{id}', name: 'delete_answer', methods: ['DELETE'])]
    public function deleteAnswer(Answer $answer, CommentAnswerManager $cam)
    {
        if (!$answer) {
            return $this->json('No answer found for id' . $answer->getId(), 404);
        }

        $cam->remove($answer);

        return $this->json('Deleted answer successfully', 200);
    }    
}

