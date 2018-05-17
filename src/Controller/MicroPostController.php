<?php

namespace App\Controller;

use App\Entity\MicroPost;
use App\Form\MicroPostType;
use App\Repository\MicroPostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use \Twig\Environment as TwigEnv;

/**
 * @Route("/micro-posts", name="micro_posts_")
 */
class MicroPostController
{
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

    public function __construct(
        TwigEnv $twig, MicroPostRepository $repo, FormFactoryInterface $formFactory, EntityManagerInterface $em, RouterInterface $router
    ) {
        $this->twig = $twig;
        $this->repository = $repo;
        $this->formFactory = $formFactory;
        $this->entityManager = $em;
        $this->router = $router;
    }
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        $html = $this->twig->render('micro_post/index.html.twig', [
            'posts' => $this->getRepository()->findAll(),
        ]);

        return new Response($html);
    }

    /**
     * @Route("/add", name="add")
     */
    public function add(Request $request)
    {
        $entity = new MicroPost;
        $entity->setTime(new \DateTime);

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
}
