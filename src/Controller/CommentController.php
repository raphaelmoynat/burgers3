<?php

namespace App\Controller;

use App\Entity\Burger;
use App\Entity\Comment;
use App\Form\BurgerType;
use App\Form\CommentType;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CommentController extends AbstractController
{
    #[Route('/comment/create/{id}', name: 'app_comment_create')]
    public function create(Request $request, EntityManagerInterface $manager, Burger $burger): Response
    {
        $comment = new Comment();
        $commentForm = $this->createForm(CommentType::class, $comment);
        $commentForm->handleRequest($request);
        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment->setBurger($burger);

            $manager->persist($comment);
            $manager->flush();
        }

        return $this->redirectToRoute('app_show', ["id" => $burger->getId()]);
    }

    #[Route('/comment/delete/{id}', name: 'delete_comment')]
    public function delete(Comment $comment, EntityManagerInterface $manager): Response
    {
        $burger = $comment->getBurger();

        $manager->remove($comment);
        $manager->flush();

        return $this->redirectToRoute('app_show', ["id" => $burger->getId()]);

    }

    #[Route('/comment/edit/{id}', name: 'edit_comment')]
    public function edit(Comment $comment, Request $request, EntityManagerInterface $manager): Response
    {
        $burger = $comment->getBurger();

        $formulaire = $this->createForm(CommentType::class, $comment);

        $formulaire->handleRequest($request);

        if ($formulaire->isSubmitted() && $formulaire->isValid()) {
            $manager->persist($comment);
            $manager->flush();
            return $this->redirectToRoute('app_show', ["id" => $burger->getId()]);
        }


        return $this->render("comment/edit.html.twig", [
            "formComment" => $formulaire->createView(),

        ]);
    }

}
