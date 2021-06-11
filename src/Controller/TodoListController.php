<?php

namespace App\Controller;

use App\Entity\TodoList;
use App\Form\TodoListType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TodoListController extends AbstractController {
    /**
     * @Route("/create-list", name="create_list")
     */
    public function create(Request $request): Response {
        $todoList = new TodoList();

        $form = $this->createForm(TodoListType::class, $todoList);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($todoList);

            $em->flush();
            return $this->redirect("/");
        }

        return $this->render('todo_list/index.html.twig', [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/", name="index")
     */
    public function readAll(): Response {
        $repo = $this->getDoctrine()->getRepository(TodoList::class);
        $lists = $repo->findAll();

        return $this->render('todo_list/read-all.html.twig', [
            "lists" => $lists
        ]);
    }

    /**
     * @Route("/update-list/{id}", name="update_list")
     */
    public function update(TodoList $list, Request $request) {
        $form = $this->createForm(TodoListType::class, $list);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute("index");
        }

        return $this->render('todo_list/index.html.twig', [
            "form" => $form->createView(),
            "list" => $list
        ]);
    }

    /**
     * @Route("/delete-list/{id}", name="delete_list")
     */
    public function delete(TodoList $list) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($list);
        $em->flush();
        return $this->redirect("/");
    }

    /**
     * @Route("/lists", name="read_lists")
     */
    public function getAll(): Response {
        $lists = $this->getDoctrine()->getRepository(TodoList::class)->findAll();
        
        $arrayOfLists = [];
        foreach ($lists as $list) {
            $arrayOfLists[] = $list->toArray();
        }

        return $this->json($arrayOfLists);
    }

    /**
     * @Route("/ajax", name="ajax")
     */
    public function ajax(): Response {
        return $this->render('ajax.html.twig');
    }
}
