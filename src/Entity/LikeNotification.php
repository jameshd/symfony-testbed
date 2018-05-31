<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LikeNotificationRepository")
 */
class LikeNotification extends Notification
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MicroPost")
     */
    private $post;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $likedBy;

    /**
     * @return MicroPost
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * @param mixed MicroPost
     */
    public function setPost($post): void
    {
        $this->post = $post;
    }

    /**
     * @return User
     */
    public function getLikedBy()
    {
        return $this->likedBy;
    }

    /**
     * @param User $likedBy
     */
    public function setLikedBy($likedBy): void
    {
        $this->likedBy = $likedBy;
    }


}
