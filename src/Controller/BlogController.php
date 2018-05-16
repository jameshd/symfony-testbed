<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

/**
 * @Route("/blog", name="blog_")
 */
class BlogController
{
    /**
     * @var Twig_Environment
     */
    private $twig;

    private $session;

    private $router;

    public function __construct(\Twig_Environment $twig, SessionInterface $session, RouterInterface $router)
    {
        $this->twig = $twig;
        $this->session = $session;
        $this->router = $router;
    }

    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        $html = $this->twig->render('blog/index.html.twig', [
            'posts' => $this->session->get('posts'),
        ]);

        return new Response($html);
    }

    /**
     * @Route("/add", name="add")
     */
    public function add()
    {
        $posts = $this->session->get('posts');
        $posts[uniqid()] = [
            'title' => 'The randomness of Posts: ' . rand(0, 500),
            'text' => "Some random text: " . rand(500, 1000),
            'date' => new \DateTime(),
        ];

        $this->session->set('posts', $posts);

        return $this->redirectToIndex();
    }

    /**
     * @Route("/show/{id}", name="show")
     */
    public function show($id)
    {
        $posts = $this->session->get('posts');

        if (!$posts || !isset($posts[$id])) {
            throw new NotFoundHttpException('post not found', 404);
        }

        $html = $this->twig->render('blog/post.html.twig', [
            'id' => $id,
            'post' => $posts[$id],
        ]);

        return new Response($html);
    }

    /**
     * @Route("/clear")
     */
    public function clear()
    {

        $this->session->clear();

        return $this->redirectToIndex();

    }

    private function redirectToIndex()
    {
        return new RedirectResponse($this->router->generate('blog_index'));
    }
}
