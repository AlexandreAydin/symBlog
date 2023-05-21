<?php

namespace App\Controller\Blog;

use App\Entity\Post\Post;
use App\Form\SearchType;
use App\Model\SearchData;
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
        $searchData = new SearchData();
        $form = $this->createForm(SearchType::class, $searchData);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $searchData->page = $request->query->getInt('page', 1);
            $posts = $postRepository->findBySearch($searchData);

            return $this->render('pages/post/index.html.twig', [
                'form' => $form->createView(),
                'posts' => $posts
            ]);

        }

        $posts =  $postRepository->findPublished( $request->query->getInt('page', 1));

        return $this->render('pages/post/index.html.twig', [
            'form' => $form->createView(),
            'posts' => $posts
        ]);
    }

    #[Route('/article/{slug}', name:'app_post.show', methods:['GET'])]
    public function show(Post $post):Response
    {
        
        return $this->render('pages/post/show.html.twig',[
            'post' =>$post
        ]);
    }
}
