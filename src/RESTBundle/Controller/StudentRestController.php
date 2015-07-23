<?php

namespace RESTBundle\Controller;

use FOS\RestBundle\Controller\Annotations\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FOS\RestBundle\Controller\FOSRestController;
use StudentBundle\Entity\School;
use StudentBundle\Entity\Student;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class StudentRestController extends FOSRestController
{

    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $student = $em->getRepository('StudentBundle:Student')->findAll();

        $view = $this->view($student, 200)
            ->setTemplate("StudentBundle:Student:index.html.twig")
            ->setTemplateVar('students')
        ;
        return $this->handleView($view);
//        return  array('student'=>$student);
    }
    public function viewAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $students = $em->getRepository('StudentBundle:Student')->find($id);
        return  array('students_'.$id=>$students);
    }

}
