<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\PostRepository;

#[Route('/', name: 'app_')]
class NavigationController extends AbstractController
{
    #[Route('', name: 'home')]
    public function index(Request $request, PaginatorInterface $paginator, PostRepository $postRepository)
    {
        $posts = $paginator->paginate(
            $postRepository->findAll(), // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            20
        );

        return $this->render('home/index.html.twig', [
            'posts' => $posts
        ]);

    }
}

