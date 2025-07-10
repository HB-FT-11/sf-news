<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\ResponseEvent;

final class AddMyCorpHeaderListener
{
    public function addHeader(ResponseEvent $event): void
    {
        // Récupérer la réponse et y ajouter mon header
        $response = $event->getResponse();

        $response->headers->add(['X-DEVELOPED-BY' => 'MyCorp']);
    }
}
