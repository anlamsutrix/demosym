<?php

namespace RESTBundle\Controller;

use FOS\RestBundle\Controller\Annotations\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FOS\RestBundle\Controller\FOSRestController;
use StudentBundle\Entity\School;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class SchoolRestController extends FOSRestController
{
     /**
     * @ApiDoc(
     * resource=true,
     * description="Get list school",
     * requirements={
     *      {"name"="_format", "dataType"="String", "requirement"="", "description"="json|xml" }
     *   },
     * statusCodes = {
     *      200 = "Returned when successful",
     *   },
     * )
     * )
     * @return View
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $school = $em->getRepository('StudentBundle:School')->findAll();
        if($school){
            $view = $this->view($school, 200)
                ->setTemplate("StudentBundle:School:api.school.html.twig")
                ->setTemplateVar('schools')
            ;
        }
        return $this->handleView($view);
//        return  array('school'=>$school);
    }
    /**
    * @ApiDoc(
     * resource=true,
     * description="Get School by id",
     * requirements={
     *      {"name"="id", "dataType"="integer", "requirement"="ID", "description"="ID for school" },
     *      {"name"="_format", "dataType"="string", "requirement"="xml | json", "description"="xml | json" }
     *   },
     * )
     * @return View
     */
    public function viewAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $school = $em->getRepository('StudentBundle:School')->find($id);
        return  array('school_'.$id=>$school);
    }
}
