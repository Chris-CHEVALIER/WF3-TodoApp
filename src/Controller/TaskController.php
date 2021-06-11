<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\TodoList;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController {
    /**
     * @Route("/create-task/{id}", name="create_task", methods={"GET","POST"})
     */
    public function create(Request $request, TodoList $list): Response {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $task->setList($list);
            $task->setCompleted(0);
            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('index');
        }

        return $this->render('task/new.html.twig', [
            'list' => $list,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/update-task/{id}", name="update_task")
     */
    public function update(Request $request, Task $task): Response {
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('index');
        }

        return $this->render('task/new.html.twig', [
            'task' => $task,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete-task/{id}", name="delete_task")
     */
    public function delete(Task $task): Response {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($task);
        $entityManager->flush();

        return $this->redirectToRoute('index');
    }

    /**
     * @Route("/update-task-status/{id}", name="update_task_status")
     */
    public function updateTaskStatus(Task $task): Response {
        $entityManager = $this->getDoctrine()->getManager();
        $task->setCompleted(!$task->getCompleted());
        $entityManager->flush();

        return $this->redirectToRoute('index');
    }
}
