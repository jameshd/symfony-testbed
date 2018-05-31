<?php
/**
 * Created by PhpStorm.
 * User: jameshd
 * Date: 22/05/2018
 * Time: 13:23
 */

namespace App\EventListener;


use App\Entity\LikeNotification;
use App\Entity\MicroPost;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\PersistentCollection;

class LikeNotificationSubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return [
            Events::onFlush
        ];
    }

    public function onFlush(OnFlushEventArgs $eventArgs)
    {
        $em = $eventArgs->getEntityManager();
        $unitOfWork = $em->getUnitOfWork();

        /** @var PersistentCollection $collectionUpdate */
        foreach ($unitOfWork->getScheduledCollectionUpdates() as $collectionUpdate) {
            if (! $collectionUpdate->getOwner() instanceof MicroPost) {
                continue;
            }

            $mapping = $collectionUpdate->getMapping();

            if ('likedBy' !== $mapping['fieldName']) {
                continue;
            }

            $insertDiff = $collectionUpdate->getInsertDiff();
            if (count($insertDiff) < 1) {
                return ;
            }
            /** @var MicroPost $post */
            $post = $collectionUpdate->getOwner();

            $notification = new LikeNotification();
            $notification->setUser($post->getUser());
            $notification->setPost($post);
            $notification->setLikedBy(reset($insertDiff));
            $em->persist($notification);
            $unitOfWork->computeChangeSet(
                $em->getClassMetadata(LikeNotification::class),
                $notification
            );
        }

    }
}