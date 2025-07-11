<?php

namespace App\Controller\Api;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

final class ArticleController extends AbstractController
{
    #[Route('/api/articles', name: 'api_articles')]
    public function list(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findAll();

        return $this->json($articles, context: [
            'groups' => ['articles:read'],
            DateTimeNormalizer::FORMAT_KEY => 'd/m/Y'
        ]);
    }
}
