<?php

namespace App\Controller;

use App\Entity\MicroPost;
use App\Form\MicroPostType;
use App\Entity\User;
use App\Repository\MicroPostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use \Twig\Environment as TwigEnv;

/**
 * @Route("/micro-posts", name="micro_posts_")
 */
class MicroPostController
{
    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    /**
     * @return type
     */
    public function getFormFa()
    {
        return $this->formFactory;
    }
    /**
     * @return TwigEnv
     */
    public function getTwig()
    {
        return $this->twig;
    }

    /**
     * @return type
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @return type
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @return type
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * @return type
     */
    public function getFlash()
    {
        return $this->flashMessages;
    }

    public function __construct(
        TwigEnv $twig, MicroPostRepository $repo, FormFactoryInterface $formFactory, EntityManagerInterface $em,
        RouterInterface $router,
        FlashbagInterface $flashMessages

    ) {
        $this->twig = $twig;
        $this->repository = $repo;
        $this->formFactory = $formFactory;
        $this->entityManager = $em;
        $this->router = $router;
        $this->flashMessages = $flashMessages;
    }
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        $html = $this->twig->render('micro_post/index.html.twig', [
            'posts' => $this->getRepository()->findBy([], [
                'time' => "DESC",
            ]),
        ]);

        return new Response($html);
    }

    /**
     * @Route("/add", name="add")
     * @Security("is_granted('ROLE_USER')")
     * @param Request $request
     * @param TokenStorageInterface $tokenStorage
     * @return RedirectResponse|Response
     */
    public function add(Request $request, TokenStorageInterface $tokenStorage)
    {
        $user = $tokenStorage->getToken()->getUser();

        $entity = new MicroPost;
        $entity->setUser($user);

        $form = $this->formFactory->create(MicroPostType::class, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($entity);
            $this->entityManager->flush();

            return new RedirectResponse($this->router->generate('micro_posts_index'));
        }

        $html = $this->twig->render('micro_post/add.html.twig', [
            'form' => $form->createView(),
        ]);

        return new Response($html);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     * @Security("is_granted('delete', post)", message="Not Authorised to Delete")
     * @param MicroPost $post
     * @return RedirectResponse
     */
    public function delete(MicroPost $post)
    {
        $this->entityManager->remove($post);
        $this->entityManager->flush();

        $this->flashMessages->add('notice', "Micro Post removed! ");

        return new RedirectResponse($this->router->generate('micro_posts_index'));
    }

    /**
     * @Route("/post/{id}", name="view")
     */
    public function post(MicroPost $post)
    {
        $html = $this->twig->render('micro_post/post.html.twig', ['post' => $post]);
        return new Response($html);
    }

    /**
     * @Route("/edit/{id}", name="edit")
     * @Security("is_granted('edit', post)", message="Not Authorised to Edit")
     * @param MicroPost $post
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function edit(MicroPost $post, Request $request)
    {
        $form = $this->formFactory->create(MicroPostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($post);
            $this->entityManager->flush();

            return new RedirectResponse($this->router->generate('micro_posts_index'));
        }

        $html = $this->twig->render('micro_post/add.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
        return new Response($html);
    }

    /**
     * @Route("/user/{username}", name="posts_by_user")
     */
    public function userposts(User $userWithPosts)
    {
        $posts = $this->getRepository()->findBy(['user' => $userWithPosts], ['time' => 'DESC']);

        $html = $this->twig->render('micro_post/user-posts.html.twig', [
            'posts' => $posts,
            'user' => $userWithPosts
        ]);

        return new Response($html);
    }
}
