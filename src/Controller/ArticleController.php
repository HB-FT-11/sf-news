<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ArticleController extends AbstractController
{
    #[Route('/articles', name: 'articles_list')]
    public function list(ArticleRepository $articleRepository): Response
    {
        // Demander les données à la BDD
        $articles = $articleRepository->findAll();

        // Rendre la vue en lui passant les données
        return $this->render('article/list.html.twig', [
            'articles' => $articles,
        ]);
    }
}
