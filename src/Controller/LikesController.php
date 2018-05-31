<?php

namespace App\Controller;

use App\Entity\MicroPost;
use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class LikesController
 * @package App\Controller
 * @Route("/likes",name="likes_")
 */
class LikesController extends Controller
{
    /**
     * @param MicroPost $postToLike
     * @Route("/like/{id}", name="like")
     * @return JsonResponse
     */
    public function like(MicroPost $post)
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        if (! $currentUser instanceof User) {
            return new JsonResponse([

            ], Response::HTTP_UNAUTHORIZED);
        }

        $post->like($currentUser);

        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse([
            'count' => $post->getLikedBy()->count()
        ], Response::HTTP_OK);
    }

    /**
     * @param MicroPost $post
     * @Route("/unlike/{id}", name="unlike")
     * @return JsonResponse
     */
    public function unlike(MicroPost $post)
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        if (! $currentUser instanceof User) {
            return new JsonResponse([

            ], Response::HTTP_UNAUTHORIZED);
        }

        $post->getLikedBy()->removeElement($currentUser);

        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse([
            'count' => $post->getLikedBy()->count()
        ], Response::HTTP_OK);
    }
}
