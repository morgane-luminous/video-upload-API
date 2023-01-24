<?php

namespace App\EventListener;

use App\Entity\Category;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: Category::class)]

class CategoryCreatedListener
{
    public function __construct(private Security $security)
    {
    }

    public function prePersist(Category $category, LifecycleEventArgs $event): void
    {
        if (false === $category->getAddedBy() instanceof User) {
            /** @var User $currentUser */
            $currentUser = $this->security->getUser();
            $category->setAddedBy($currentUser);
        }
    }
}