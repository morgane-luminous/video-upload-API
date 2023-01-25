<?php

namespace App\EventListener;

use App\Entity\Category;
use App\Entity\User;
use App\Entity\Video;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Symfony\Bundle\SecurityBundle\Security;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: Video::class)]

class VideoCreatedListener
{
    public function __construct(private Security $security)
    {
    }

    public function prePersist(Video $video, LifecycleEventArgs $event): void
    {
        if (false === $video->getAddedBy() instanceof User) {
            /** @var User $currentUser */
            $currentUser = $this->security->getUser();
            $video->setAddedBy($currentUser);
        }
    }
}