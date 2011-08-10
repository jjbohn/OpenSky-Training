<?php

namespace Train\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Train\MainBundle\Entity\Product;
use Doctrine\ORM\EntityManager;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $categories = $em->getRepository('MainBundle:Category')->findAll();

        return $this->render('MainBundle:Default:index.html.twig', array(
            'categories' => $categories
        ));
    }

    private function parseProductToJson($products)
    {
        $data = array();
        foreach($products as $product) {
            $data[$product->getId()]['name'] = $product->getName();
            $data[$product->getId()]['description'] = $product->getDescription();
            $data[$product->getId()]['price'] = $product->getPrice();
        }
        
        return json_encode($data);
    }
    
    public function listAction($_format)
    {
        $em = $this->getDoctrine()->getEntityManager();
        
        $products = $em->getRepository('MainBundle:Product')->findAllNewestFirst();

        if($_format == 'html') {
            return $this->render('MainBundle:Default:product_list.html.twig', array(
                 'products' => $products  
            ));
        } elseif ($_format ==  'json') {
            $response = new Response();
            $response->setContent($this->parseProductToJson($products));
            return $response;
            //return $this->render('MainBundle:Default:product_list.json.php', array(
            //    'products' => $products
            //));
            
        } else {
            //throw $this->createNotFoundException('Invalid format requested');
            //$exp = new HttpException('415', 'Invalid format requested', null, array('Content-Type' => 'application/javascript') );
            //throw $exp;
            return new Response('Invalid format requested', 415, array('Content-Type' => 'application/javascript'));
        }
        
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

    public function categoryAction($category_name)
    {
        // $em = $this->
    }

    // private function getEntityManager()
    // {
    //     $em = $
    // }
}