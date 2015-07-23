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
use StudentBundle\Form\StudentType;

/**
 * Student controller.
 *
 */
class StudentController extends Controller
{

    /**
     * Lists all Student entities.
     *
     */
    public function indexAction(Request $request)
    {
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

        $repository = $this->getDoctrine()
                           ->getRepository('StudentBundle:Student');
        if ($form->isSubmitted()){
            $data = $request->query->get($form->getName());
            $students = $repository->getStudentByName($data['name']);
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
     * Creates a new Student entity.
     *
     */
    public function createAction(Request $request)
    {

        $repository = $this->getDoctrine()
                           ->getRepository('StudentBundle:Student');
        $entity = new Student();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $entity->uploadImage();
            $file = $entity->getBrochure();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $brochuresDir = $_SERVER['DOCUMENT_ROOT'].'uploads/brochures';
            $file->move($brochuresDir, $fileName);
            $entity->setBrochure($fileName);
            $school_id = $form->get('school_id')->getData();
            $em = $this->getDoctrine()->getManager();
            $entity->setPosition($repository->getPositionMax($school_id) + 1);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('student_show', array('id' => $entity->getId())));
        }

        return $this->render('StudentBundle:Student:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }


        /**
     * Creates a form to create a Student entity.
     *
     * @param Student $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Student $entity)
    {
        $form = $this->createForm(new StudentType(), $entity, array(
            'action' => $this->generateUrl('student_create'),
            'method' => 'POST',
        ));
        $form->add('school_id', 'hidden');
        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Student entity.
     *
     */
    public function newAction()
    {
        $entity = new Student();
        $form   = $this->createCreateForm($entity);

        return $this->render('StudentBundle:Student:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Student entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('StudentBundle:Student')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Student entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('StudentBundle:Student:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Student entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('StudentBundle:Student')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Student entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editImageForm = $this->createEditImageForm($entity);
        $editBrochureForm = $this->createEditBrochureForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('StudentBundle:Student:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'edit_image_form' => $editImageForm->createView(),
            'edit_brochure_form' => $editBrochureForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Student entity.
    *
    * @param Student $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Student $entity)
    {
        $form = $this->createForm(new StudentType(), $entity, array(
            'action' => $this->generateUrl('student_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));
        $form->remove('image', 'file');
        $form->remove('brochure', 'file');

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
    * Creates a form to edit Brochure Student entity.
    *
    * @param Student $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditBrochureForm(Student $entity)
    {
        $form = $this->createForm(new StudentType(), $entity, array(
            'action' => $this->generateUrl('student_update_brochure', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));
        $form->remove('school');
        $form->remove('name');
        $form->remove('age');
        $form->remove('render');
        $form->remove('image');

        $form->add('submit', 'submit', array('label' => 'Update Brochure'));

        return $form;
    }

    /**
    * Creates a form to edit image Student entity.
    *
    * @param Student $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditImageForm(Student $entity)
    {
        $form = $this->createForm(new StudentType(), $entity, array(
            'action' => $this->generateUrl('student_update_image', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));
        $form->remove('school');
        $form->remove('name');
        $form->remove('age');
        $form->remove('render');
        $form->remove('brochure');

        $form->add('submit', 'submit', array('label' => 'Update Image'));

        return $form;
    }

    /**
     * Edits an existing Student entity.
     * @param Student $entity, $id
     */
    public function updateBprAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('StudentBundle:Student')->find($id);
        $brochure_old = $entity->getBrochure();
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Student entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);

        $editImageForm = $this->createEditImageForm($entity);
        $editImageForm = $this->createEditImageForm($entity);
        $editBrochureForm = $this->createEditBrochureForm($entity);

        $editBrochureForm->handleRequest($request);

        if ($editBrochureForm->isSubmitted()) {
           $file = $entity->getBrochure();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $brochuresDir = $_SERVER['DOCUMENT_ROOT'].'uploads/brochures';
            $file->move($brochuresDir, $fileName);
            if($brochure_old){
                unlink($brochuresDir.'/'.$brochure_old);
            }
            $entity->setBrochure($fileName);
            $em->flush();

            return $this->redirect($this->generateUrl('student_show', array('id' => $id)));
        }

        return $this->render('StudentBundle:Student:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'edit_image_form' => $editImageForm->createView(),
            'edit_brochure_form' => $editBrochureForm->createView(),
        ));
    }

    /**
     * Edits an existing Student entity.
     * @param Student $entity, $id
     */
    public function updateImageAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('StudentBundle:Student')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Student entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);

        $editImageForm = $this->createEditImageForm($entity);
        $editImageForm->handleRequest($request);

        if ($editImageForm->isSubmitted()) {
            $entity->uploadImage();
            $em->flush();

            return $this->redirect($this->generateUrl('student_show', array('id' => $id)));
        }

        return $this->render('StudentBundle:Student:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'edit_image_form' => $editImageForm->createView(),
        ));
    }
    /**
     * Edits an existing Student entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('StudentBundle:Student')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Student entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editBrochureForm = $this->createEditBrochureForm($entity);
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted()) {
            $em->flush();
            return $this->redirect($this->generateUrl('student_show', array('id' => $id)));
        }

        return $this->render('StudentBundle:Student:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'edit_brochure_form' => $editBrochureForm->createView(),
        ));
    }
    /**
     * Deletes a Student entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('StudentBundle:Student')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Student entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('student'));
    }

    /**
     * Creates a form to delete a Student entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('student_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
    /*
     * move up position
     */
    public function upAction(){

        $data = explode(',', $_POST['id']);
        $id = $data[0];
        $school_id = $data[1];
        $em = $this->getDoctrine()->getManager();
        $student = $em->getRepository('StudentBundle:Student')->findOneBy(array('id'=>$id, 'school'=> $school_id));
        $this->updatePositionForUp($student->getPosition() + 1, $school_id);
        $student->setPosition($student->getPosition() + 1);

        $em->persist($student);
        $em->flush();
    }
    /*
     * move down position
     */
    public function downAction(){
        $data = explode(',', $_POST['id']);
        $id = $data[0];
        $school_id = $data[1];
        $em = $this->getDoctrine()->getManager();
        $student = $em->getRepository('StudentBundle:Student')->findOneBy(array('id'=>$id, 'school'=>$school_id));
        $this->updatePositionForDown($student->getPosition() - 1, $school_id);
        $student->setPosition($student->getPosition() - 1);
        $em->persist($student);
        $em->flush();
    }
    /*
     * update position for action up
     * @param integer $school_id, $position
     */
    public function updatePositionForUp($position,$school_id){

        $em = $this->getDoctrine()->getManager();
        $student = $em->getRepository('StudentBundle:Student')->findOneBy(array('position' => $position, 'school'=>$school_id));
        $student->setPosition($student->getPosition() - 1);
        $em->persist($student);
        $em->flush();
        return $student->getPosition();
    }
    /*
     * update position for action down
     * @param integer $school_id, $position
     */
    public function updatePositionForDown($position,$school_id){

        $em = $this->getDoctrine()->getManager();
        $student = $em->getRepository('StudentBundle:Student')->findOneBy(array('position' => $position, 'school'=>$school_id));
        $student->setPosition($student->getPosition() + 1);
        $em->persist($student);
        $em->flush();
        return $student->getPosition();
    }
}
