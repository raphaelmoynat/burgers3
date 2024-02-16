<?php

namespace App\Controller;

use App\Entity\Burger;
use App\Entity\Comment;
use App\Form\BurgerType;
use App\Form\CommentType;
use App\Form\CreateBurgerType;
use App\Repository\BurgerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BurgerController extends AbstractController
{
    #[Route('/burger', name: 'app_burger')]
    public function index(BurgerRepository $burgerRepository): Response
    {


        return $this->render('burger/index.html.twig', [
            'controller_name' => 'BurgerController',
            "burgers"=>$burgerRepository->findAll(),
        ]);
    }

    #[Route('/burger/{id}', name: 'app_show', priority: -1)]
    public function show(Burger $burger): Response
    {
        $comment = new Comment();
        $formComment = $this->createForm(CommentType::class, $comment);


        return $this->render('burger/show.html.twig', [
            'controller_name' => 'BurgerController',
            "burger"=>$burger,
            "formComment"=>$formComment->createView()
        ]);
    }

    #[Route('/burger/create', name: 'app_create')]
    public function create(Request $request, EntityManagerInterface $manager): Response
    {
        $burger = new Burger();

        $formulaire = $this->createForm(BurgerType::class, $burger);

        $formulaire->handleRequest($request);

        if($formulaire->isSubmitted() && $formulaire->isValid())
        {
            $manager->persist($burger);
            $manager->flush();

            return $this->redirectToRoute('app_burger');
        }


        return $this->render('burger/create.html.twig', [
            "formulaire"=>$formulaire->createView()
        ]);
    }

    #[Route('/burger/delete/{id}', name: 'delete_burger')]
    public function delete(Burger $burger,EntityManagerInterface $manager)
    {

        $manager->remove($burger);
        $manager->flush();

        return $this->redirectToRoute("app_burger");

    }

    #
    #[Route('/burger/edit/{id}', name: 'edit_burger')]
    public function edit(Burger $burger, Request $request, EntityManagerInterface $manager): Response
    {

        $formulaire = $this->createForm(BurgerType::class,$burger);

        $formulaire->handleRequest($request);

        if($formulaire->isSubmitted() && $formulaire->isValid()){
            $manager->persist($burger);
            $manager->flush();
            return $this->redirectToRoute("app_burger");
        }



        return $this->render("burger/create.html.twig",[
            "formulaire"=>$formulaire->createView(),

        ]);
    }




}
