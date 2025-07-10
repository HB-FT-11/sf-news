<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ArticleController extends AbstractController
{
    #[Route('/articles', name: 'articles_list')]
    public function list(ArticleRepository $articleRepository): Response
    {
        // Demander les données à la BDD
        $articles = $articleRepository->findRecentArticlesWithCategory();

        // Rendre la vue en lui passant les données
        return $this->render('article/list.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/articles/read/{id}', name: 'article_read')]
    public function read(Article $article): Response
    {
        return $this->render('article/item.html.twig', [
            'article' => $article
        ]);
    }
}
