<?php

namespace App\Serializer\Normalizer;

use App\Entity\Article;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ArticleNormalizer implements NormalizerInterface
{
    public function __construct(
        #[Autowire(service: 'serializer.normalizer.object')]
        private NormalizerInterface $normalizer,
        private UrlGeneratorInterface $router
    ) {
    }

    public function normalize($object, ?string $format = null, array $context = []): array
    {
        $originalData = $this->normalizer->normalize($object, $format, $context);
        $originalData['url'] = $this->router->generate(
            'article_read', // nom de la route
            ['id' => $originalData['id']], // Paramètre d'URL
            UrlGeneratorInterface::ABSOLUTE_URL // URL absolue
        );

        return $originalData;
    }

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof Article;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [Article::class => true];
    }
}
