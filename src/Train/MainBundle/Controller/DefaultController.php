<?php

namespace Train\MainBundle\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Train\MainBundle\Entity\Product;

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

    public function createProductAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $categories = $em->getRepository('MainBundle:Category')
            ->findAll();

        $product = new Product();

        $form = $this->createFormBuilder($product)
            ->add('name', 'text', array(
                'required' => true,
                'max_length' => 100,
                'label' => 'Product Name',
            ))
            ->add('price', 'money', array(
                'currency' => 'USD',
                'required' => true,
                'label' => 'Cost',
            ))
            ->add('description', 'textarea', array(
                'required' => false,
                'label' => 'Product Description',
            ))
            ->add('category', 'entity', array(
                'class' => 'MainBundle:Category',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.name');
                }
            ))
            ->getForm();

        if ($request->getMethod() === 'POST') {
            // Persist the product
            $form->bindRequest($request);

            if ($form->isValid()) {
                $em->persist($product);
                $em->flush();

                return $this->redirect($this->generateUrl('homepage'));
            }
        }

        return $this->render('MainBundle:Default:create.html.twig', array(
            'form' => $form->createView()
        ));
        return new Response('create');
    }

    public function reportAction()
    {
        $manager = $this->get('report_manager');
        
        return $this->renderResponse($manager->generate());
    }
    
    // private function getEntityManager()
    // {
    //     $em = $
    // }
}
