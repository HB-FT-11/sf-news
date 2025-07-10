<?php

namespace App\EventListener;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsDoctrineListener('prePersist')]
final class HashUserPasswordListener
{
    public function __construct(
        private UserPasswordHasherInterface $hasher
    ) {
    }

    public function prePersist(PrePersistEventArgs $args): void
    {
        $object = $args->getObject();

        if (!$object instanceof User) {
            return;
        }

        // Il s'agit bien d'un User, donc : hachage du mot de passe
        $object->setPassword(
            $this->hasher->hashPassword($object, $object->getPassword())
        );
    }
}
