<?php

namespace Train\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Train\MainBundle\Entity\Product;
use Doctrine\ORM\EntityManager;

class DefaultController extends Controller
{
    public function indexAction($name)
    { 
        $product = new Product();
        $product->setDescription('A description');
        $product->setName('The Shirt');
        $product->setPrice(50);
        
        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($product);
        $em->flush();
        
        return $this->render('MainBundle:Default:index.html.twig', array(
            'name' => $name
        ));
    }
}
