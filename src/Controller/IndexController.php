<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class IndexController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function home(): Response
    {
        $names = ['Mable', 'Lee', 'Alberta', 'Minerva', 'Melvin', 'Verna'];
        // index aléatoire entre 0 et la taille du tableau - 1
        $idx = random_int(0, count($names) - 1);

        return $this->render('index/index.html.twig', [
            'firstname' => $names[$idx],
        ]);
    }
}
