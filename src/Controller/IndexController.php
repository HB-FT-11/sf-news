<?php

namespace App\Controller;

use App\Entity\NewsletterSubscriber;
use App\Form\NewsletterForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
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

    #[Route('/about-us', name: 'about')]
    public function about(): Response
    {
        return $this->render('index/about.html.twig');
    }

    #[Route('/newsletter/subscribe', name: 'newsletter_subscribe')]
    public function newsletterSubscribe(
        Request $request,
        EntityManagerInterface $em, // Dépendance : em = Entity Manager = Gestionnaire d'entités
        MailerInterface $mailer
    ): Response {
        // Je crée une instance d'un inscrit
        $newsletterSubscriber = new NewsletterSubscriber();
        // Je crée un formulaire de newsletter et j'y relie l'instance
        $newsletterForm = $this->createForm(NewsletterForm::class, $newsletterSubscriber);

        // Je passe les données de la requête au formulaire
        // pour qu'il détermine s'il doit traiter les données POST ou non
        $newsletterForm->handleRequest($request);

        if ($newsletterForm->isSubmitted() && $newsletterForm->isValid()) {
            $em->persist($newsletterSubscriber);
            $em->flush();

            $this->addFlash('success', "Votre inscription a bien été prise en compte");

            // Envoyer un email à l'utilisateur
            $email = (new Email())
                ->from('admin@hbcorp.com')
                ->to($newsletterSubscriber->getEmail())
                ->subject('Merci pour votre inscription !')
                ->text('Votre inscription à la newsletter SF News a bien été prise en compte, merci !')
                ->html('<p>Votre inscription à la newsletter SF News a bien été prise en compte, merci !</p>');

            $mailer->send($email);

            return $this->redirectToRoute('homepage');
        }

        return $this->render('index/newsletter_subscribe.html.twig', [
            'newsletter_form' => $newsletterForm
        ]);
    }
}
