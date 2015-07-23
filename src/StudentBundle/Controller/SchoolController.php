<?php

namespace StudentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use StudentBundle\Entity\School;
use StudentBundle\Entity\Student;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\ArrayCollection;

class SchoolController extends Controller
{
    /**
     * Index Action
     * Displays a list School entity.
     * @param Request $request request object
     * @return view
     *
     */

    public function indexAction(Request $request)
    {
        $repository = $this->getDoctrine()
                           ->getRepository('StudentBundle:School');
        $schools = $repository->findBy(array(), array('position'=>'DESC'));

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $schools,
            $request->query->getInt('page', 1)/*page number*/,
            4/*limit per page*/
        );
        return $this->render('StudentBundle:School:index.html.twig',
                array('schools' => $pagination,
                    'link_add' => $this->generateUrl('school_add'),
                    'max' => $repository->getPositionMax(),
                    'min' => $repository->getPositionMin()
               ));
    }
    /**
     * Displays a form to create a new School.
     *
     * @param Request $request request object
     * @return Redirect
     *
     */
    public function addAction(Request $request)
    {
        $school = new School();
        $form = $this->createFormBuilder($school)
            ->add('name', 'text', array('required' => true,))
            ->add('address', 'text',array('required' => true,))
            ->add('phone', 'text',array('required' => true,))
            ->add('save', 'submit', array('label' => 'Create School'))
            ->getForm();


        if ($form->handleRequest($request)->isSubmitted()) {
            if ($form->isValid()) {
                $repository = $this->getDoctrine()
                           ->getRepository('StudentBundle:School');
                $em = $this->getDoctrine()->getManager();

                $school->setPosition($repository->getPositionMax() + 1);

                $em->persist($school);
                $em->flush();
                return $this->redirect($this->generateUrl('school'));
            }
        }
        return $this->render('StudentBundle:School:add.html.twig',
                array('form' => $form->createView(),
               ));
    }
    /**
     * Displays a form to edit an existing School.
     *
     * @param Request        $request
     * @param integer|string $id
     *
     * @return Redirect
     *
     */
    public function updateAction(Request $request, $id)
    {
        if (is_null($id)) {
            return $this->redirect($this->generateUrl('school'));
        }
        $em = $this->getDoctrine()->getEntityManager();
        $school = $em->getRepository('StudentBundle:School')->find($id);
        $form = $this->createFormBuilder($school)
            ->add('name', 'text')
            ->add('address', 'text')
            ->add('phone', 'text')
            ->add('save', 'submit', array('label' => 'Update School'))
            ->getForm();
        if ($form->handleRequest($request)->isSubmitted()) {

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($school);
                $em->flush();
                return $this->redirect($this->generateUrl('school'));
            }
        }
        return $this->render('StudentBundle:School:update.html.twig',
                array('form' => $form->createView(),
               ));
    }
    /**
     * Deletes a School.
     *
     * @param integer|string $id
     *
     * @return Redirect
     *
     */
    public function removeAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $school = $em->getRepository('StudentBundle:School')->find($id);
        $em->remove($school);
        $em->flush();
        return $this->redirect($this->generateUrl('school'));
    }
    /*
     * move up position
     */
    public function upAction(){
        $id = $_POST['id'];
        $em = $this->getDoctrine()->getManager();
        $school = $em->getRepository('StudentBundle:School')->find($id);
        $this->updatePositionForUp($school->getPosition() + 1);
        $school->setPosition($school->getPosition() + 1);

        $em->persist($school);
        $em->flush();
    }
    /*
     * move down position
     */
    public function downAction(){
        $id = $_POST['id'];
        $em = $this->getDoctrine()->getManager();
        $school = $em->getRepository('StudentBundle:School')->find($id);
        $this->updatePositionForDown($school->getPosition() - 1);
        $school->setPosition($school->getPosition() - 1);
        $em->persist($school);
        $em->flush();
    }
    /**
     * update position for action up
     * @param integer $position
     */
    public function updatePositionForUp($position){

        $em = $this->getDoctrine()->getManager();
        $school = $em->getRepository('StudentBundle:School')->findOneBy(array('position' => $position));
        $school->setPosition($school->getPosition() - 1);
        $em->persist($school);
        $em->flush();
    }
    /**
     * update position for action down
     * @param integer $position
     */
    public function updatePositionForDown($position){

        $em = $this->getDoctrine()->getManager();
        $school = $em->getRepository('StudentBundle:School')->findOneBy(array('position' => $position));
        $school->setPosition($school->getPosition() + 1);
        $em->persist($school);
        $em->flush();
        return $school->getPosition();
    }
    /**
     * get student by school_id
     * @param integer $id
     */
    public function listStudentAction(Request $request,$id){
        $repository = $this->getDoctrine()
                           ->getRepository('StudentBundle:Student');

        $student = new Student();
        $form = $this->createFormBuilder($student, array(
            'method' => 'GET',))
                ->add('name', 'search' , array('required'=> FALSE,
                                'attr' => array(
                                        'placeholder' => 'Your name',
                                        'expanded' => true,
                                        ),
                                        'label' => false,
                                ))
                ->add('search', 'submit', array('label' => 'Search'))
                ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()){
            $data = $request->query->get($form->getName());
            $student = $repository->searchStudentBySchool($data['name'],$id);
        }  else {
            $student = $repository->findBy(array('school'=>$id), array('position'=>'DESC'));
        }

        $paginator  = $this->get('knp_paginator');
            $pagination = $paginator->paginate(
                $student,
                $request->query->getInt('page', 1)/*page number*/,
                5/*limit per page*/
        );
        return $this->render('StudentBundle:School:student.by.school.html.twig',
                array('students' => $pagination,
                    'form' => $form->createView(),
                    'max' => $repository->getPositionMax($id),
                    'min' => $repository->getPositionMin($id),
                    'count' => count($student),
                    'school_id'=> $id
               ));
    }

}
