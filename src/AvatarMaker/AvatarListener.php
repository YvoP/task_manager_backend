<?php

namespace App\AvatarMaker;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Events;
use LasseRafn\InitialAvatarGenerator\InitialAvatar;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

#[AsDoctrineListener(event: Events::prePersist, priority: 500)]
class AvatarListener
{

    public function __construct(
        private readonly ParameterBagInterface $params)
    {
    }

    public function prePersist(PrePersistEventArgs $eventArgs): void {
        $object = $eventArgs->getObject();

        if ($object instanceof User) {
            $avatar = new InitialAvatar();
            $image = $avatar->name($object->getUsername())->autoColor()->generate();
            $image->stream('png', 100);

            $filename = uniqid('avatar_', true) . '.png';
            $path = $this->params->get('kernel.project_dir')
                . '/assets/images/profilePictures/'
                . $filename;
            file_put_contents($path, $image);

            $object->setProfileImage($filename);
        }
    }
}
