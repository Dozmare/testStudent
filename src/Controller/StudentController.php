<?php

namespace App\Controller;

use App\Entity\Student;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\StudentType;
use App\Repository\StudentRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class StudentController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(StudentRepository $etud): Response
    {
        $etudList = $etud->findAll();
        return $this->render('student/index.html.twig', [
            'controller_name' => 'StudentController',
            'etudList' => $etudList,

        ]);
    }

    /**
     * @Route("/new", name="add")
     */
    public function addAction(Request $request, EntityManagerInterface $entityManager): Response
    {
        $student = New Student();

        $form = $this->createForm(StudentType::class, $student);

        $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()){

            $entityManager->persist($student);
            $entityManager->flush();
        }
        return $this->render('student/add.html.twig', [
            'controller_name' => 'StudentController',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/user/{id}", name="update")
     */

    public function updateAction(?Student $student,Request $request, EntityManagerInterface $entityManager): Response
    {
        if(!$student)
        {
            $student = New Student();
        }

        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()){
        if(!$student->getId())
        {
            $entityManager->persist($student);
        }
            $entityManager->flush();

            return $this->redirect($this->generateUrl('update', ['id' => $student->getId()]));
        }

        return $this->render('student/add.html.twig', [
            'controller_name' => 'StudentController',
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/delete", name="delete")
     */

    public function deleteAction(?Student $student,Request $request, EntityManagerInterface $entityManager): Response
    {

        if(!$student->getId())
        {
            $entityManager->persist($student);
        }
            $entityManager->flush();

            return $this->redirect($this->generateUrl('delete', ['id' => $student->getId()]));
    }

}
