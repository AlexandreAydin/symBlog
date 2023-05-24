<?php

namespace App\Controller\Post;

use App\Entity\Post\Comment;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class CommentController extends AbstractController
{
    #[Route('/comment/{id}', name: 'app_comment.delete')]
    #[IsGranted('ROLE_USER')]
    public function delete(UserInterface $user, EntityManagerInterface $em, Request $request, $id): Response
    {
        $comment = $em->getRepository(Comment::class)->find($id);
    
        if (!$comment) {
            throw $this->createNotFoundException('Commentaire non trouvé');
        }
    
        // Vérifie si l'utilisateur courant est l'auteur du commentaire
        if ($user !== $comment->getAuthor()) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à supprimer ce commentaire.');
        }
    
        $params = ['slug' => $comment->getPost()->getSlug()];
        if ($this->isCsrfTokenValid('delete' . $comment->getId(), $request->request->get('_token'))) {
            $em->remove($comment);
            $em->flush();
        }
    
        $this->addFlash('success', 'Votre commentaire a bien été supprimé.');
    
        return $this->redirectToRoute('app_post.show', $params);
    }
    
}
