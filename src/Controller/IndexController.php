<?php

namespace App\Controller;

use App\Entity\NewsletterSubscriber;
use App\Form\NewsletterForm;
use App\Mail\NewsletterSubscribedConfirmation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

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

    #[Route('/about-us', name: 'about')]
    public function about(): Response
    {
        return $this->render('index/about.html.twig');
    }

    #[Route('/newsletter/subscribe', name: 'newsletter_subscribe')]
    public function newsletterSubscribe(
        Request $request,
        EntityManagerInterface $em, // Dépendance : em = Entity Manager = Gestionnaire d'entités
        NewsletterSubscribedConfirmation $confirmationService,
        HttpClientInterface $client
    ): Response {
        // Je crée une instance d'un inscrit
        $newsletterSubscriber = new NewsletterSubscriber();
        // Je crée un formulaire de newsletter et j'y relie l'instance
        $newsletterForm = $this->createForm(NewsletterForm::class, $newsletterSubscriber);

        // Je passe les données de la requête au formulaire
        // pour qu'il détermine s'il doit traiter les données POST ou non
        $newsletterForm->handleRequest($request);

        if ($newsletterForm->isSubmitted() && $newsletterForm->isValid()) {
            // Vérifier que l'email n'est pas un spam
            $response = $client->request('POST', 'http://localhost:8001/api/check', [
                'json' => ['email' => $newsletterSubscriber->getEmail()]
            ]);
            $data = $response->toArray();

            if ($data['result'] === 'spam') {
                $newsletterForm->addError(new FormError("Une erreur est survenue lors de la vérification de l'email"));
            } else {
                $em->persist($newsletterSubscriber);
                $em->flush();

                $this->addFlash('success', "Votre inscription a bien été prise en compte");

                // Envoyer un email à l'utilisateur
                $confirmationService->send($newsletterSubscriber);

                return $this->redirectToRoute('homepage');
            }
        }

        return $this->render('index/newsletter_subscribe.html.twig', [
            'newsletter_form' => $newsletterForm
        ]);
    }
}
