<?php

namespace App\Controller\Blog;

use App\Repository\Post\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    #[Route('/', name: 'app_post')]
    public function index(PostRepository $postRepository,
    Request $request,
    ): Response
    {
        $posts =  $postRepository->findPublished( $request->query->getInt('page', 1));


        return $this->render('pages/post/index.html.twig', [
            'posts' => $posts
        ]);
    }
}
