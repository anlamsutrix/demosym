<?php

namespace StudentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use StudentBundle\Entity\School;
use StudentBundle\Entity\Student;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\ArrayCollection;


class StudentController extends Controller
{
    /**
     * Index Action
     * Displays a list Student entity.
     * document
     *
     */
    public function indexAction(Request $request)
    {
        echo "test v2.0 true";
        $student = new Student();
        $form = $this->createFormBuilder($student)
                ->add('name', 'search' , array('required'=> FALSE,
                    'attr' => array(
                            'placeholder' => 'Your name',
                            ),
                            'label' => false,
                    ))
                ->add('search', 'submit', array('label' => 'Search'))
                ->getForm();
        $form->handleRequest($request);
        $repository = $this->getDoctrine()
                           ->getRepository('StudentBundle:Student');
        if ($form->isValid() && $form->isSubmitted() && $student->getName()){
            $students = $repository->getStudentByName($student->getName());
        }  else {
            $students = $repository->getOrderBy('ASC');
        }
        $paginator  = $this->get('knp_paginator');
            $pagination = $paginator->paginate(
                $students,
                $request->query->getInt('page', 1)/*page number*/,
                5/*limit per page*/
        );
        return $this->render('StudentBundle:Student:index.html.twig',
                array('students' => $pagination,
                    'form' => $form->createView(),
               ));
    }
    /**
     * Add Action
     * Displays a form to create a new Student.
     *
     * @param Request $request request object
     * @return Redirect
     *
     */
    public function addAction(Request $request)
    {
        $student = new Student();
        $school = new School();
        $form = $this->createFormBuilder($student)
                ->add('school', 'entity', array(
                        'class' => 'StudentBundle:School',
                        'property' => 'name',
                        'label' => 'School : ',
                        )
                    )
                ->add('name', 'text' , array('required'=> true,'error_bubbling' => true))
                ->add('image', 'file', array('required'=> true))
                ->add('age', 'text' , array('required'=> true))
                ->add('render', 'choice', array('choices'=> array('Male' => 'Male', 'Female' => 'Female')))
                ->add('save', 'submit', array('label' => 'Create Student'))
                ->getForm();
        $form->handleRequest($request);
        $student->uploadImage();
        if ($form->isValid() && $form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($student);
            $em->flush();
            return $this->Redirect($this->generateUrl('student'));
        }

        return $this->render('StudentBundle:Student:add.html.twig',
                array('form' => $form->createView()
                ));
    }
    public function updateAction(Request $request, $id)
    {
        if (is_null($id)) {
            return $this->redirect($this->generateUrl('student'));
        }
        $em = $this->getDoctrine()->getEntityManager();
        $student = $em->getRepository('StudentBundle:Student')->find($id);
        $form = $this->createForm(new \StudentBundle\Form\StudentType(), $student, array(
            'action' => $this->generateUrl('student_update',array('id' => $student->getId())),
//            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $this->render('StudentBundle:Student:update.html.twig',
                array('form' => $form->createView(),
               ));
    }
    public function testAction(Request $request){
        $repository = $this->getDoctrine()
                           ->getRepository('StudentBundle:Student');
        $students = $repository->getStudentLeftJoin(2);
        $paginator  = $this->get('knp_paginator');
            $pagination = $paginator->paginate(
                $students,
                $request->query->getInt('page', 1)/*page number*/,
                5/*limit per page*/
        );
        return $this->render('StudentBundle:Default:index.html.twig',
                array('students' => $pagination,
               ));
    }
}
