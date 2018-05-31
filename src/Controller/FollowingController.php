<?php

namespace App\Controller;

use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class FollowingController
 * @package App\Controller
 * @Security("is_granted('ROLE_USER')")
 * @Route("/following", name="following_")
 */
class FollowingController extends Controller
{
    /**
     * @Route("/follow/{id}", name="follow")
     */
    public function follow(User $userToFollow)
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        if ($currentUser->getId() !== $userToFollow->getId()) {
            $currentUser->follow($userToFollow);
            $this->getDoctrine()->getManager()->flush();
        }

        return $this->redirectToRoute('micro_posts_posts_by_user', [
            'username'=>$userToFollow->getUsername()
        ]);

    }

    /**
     * @Route("/unfollow/{id}", name="unfollow")
     */
    public function unfollow(User $userToUnfollow)
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        $currentUser->getFollowing()->removeElement($userToUnfollow);

        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('micro_posts_posts_by_user', [
            'username' => $userToUnfollow->getUsername()
        ]);
    }

}
