<?php

namespace BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use BlogBundle\Entity\Product;

class DefaultController extends Controller
{
    /**
     * @Route("/product/add")
     * @Template()
     */
    public function indexAction()
    {
        $product = new Product();
        $product->setName('A Foo Bar');
        $product->setPrice('19.99');
        $product->setDescription('Lorem ipsum dolor');

        $em = $this->getDoctrine()->getManager();

        $em->persist($product);
        $em->flush();

        return new Response('Created product id '.$product->getId());
    }
    /**
     * @Route("/product/show/{id}")
     * @Template()
     */
    public function showAction($id)
    {
        $product = $this->getDoctrine()
            ->getRepository('BlogBundle:Product')
            ->find($id);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }
        return new Response('Info Product -> '.$product->getName());
        // ... do something, like pass the $product object into a template
    }
}
