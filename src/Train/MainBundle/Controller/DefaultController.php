<?php

namespace Train\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Train\MainBundle\Entity\Product;
use Doctrine\ORM\EntityManager;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('MainBundle:Default:index.html.twig');
    }

    public function listAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        
        $products = $em->getRepository('MainBundle:Product')->findAllNewestFirst();

        return $this->render('MainBundle:Default:product_list.html.twig', array(
             'products' => $products  
        ));
    }
     
    public function showAction($product_slug)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $product = $em
            ->getRepository('MainBundle:Product')
            ->findOneBySlug($product_slug);

        if ($product === null) {
            $str = sprintf('No product matched "%s"',$product_slug);
            throw $this->createNotFoundException($str);
        }

        return $this->render('MainBundle:Default:show.html.twig', array(
            'product' => $product
        ));
    }
}