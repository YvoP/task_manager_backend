<?php
namespace App\Slugger;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;

#[AsDoctrineListener(event: Events::preUpdate, priority: 500)]
#[AsDoctrineListener(event: Events::prePersist, priority: 500)]
readonly class SlugListener
{

    public function __construct(private SlugService $slugifyService)  { }

    public function prePersist(PrePersistEventArgs $eventArgs): void {
        $this->slugify($eventArgs);
    }

    public function preUpdate(PreUpdateEventArgs $eventArgs): void {
        $this->slugify($eventArgs);
    }

    private function slugify(LifecycleEventArgs $lifecycleEventArgs): void {
        $object = $lifecycleEventArgs->getObject();

        if ($object instanceof SlugInterface) {
            $object->setSlug($this->slugifyService->slugify($object->getFields()));
        }
    }
}
