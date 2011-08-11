<?php

namespace Train\MainBundle\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Train\MainBundle\Entity\Product;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $em = $this->container->get('doctrine.orm.default_entity_manager');
        $categories = $em->getRepository('MainBundle:Category')->findAll();

        return $this->container->get('templating')
            ->renderResponse('MainBundle:Default:index.html.twig', array(
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
        $em = $this->container->get('doctrine.orm.default_entity_manager');
        
        $products = $em->getRepository('MainBundle:Product')->findAllNewestFirst();

        if($_format == 'html') {
            return $this->container->get('templating')->renderResponse('MainBundle:Default:product_list.html.twig', array(
                 'products' => $products  
            ));
        } elseif ($_format ==  'json') {
            $response = new Response();
            $response->setContent($this->parseProductToJson($products));
            return $response;
        } else {
            //throw $this->createNotFoundException('Invalid format requested');
            //$exp = new HttpException('415', 'Invalid format requested', null, array('Content-Type' => 'application/javascript') );
            //throw $exp;
            return new Response('Invalid format requested', 415, array('Content-Type' => 'application/javascript'));
        }
        
    }
     
    public function showAction($product_slug)
    {
        $em = $this->container->get('doctrine.orm.default_entity_manager');
        $product = $em
            ->getRepository('MainBundle:Product')
            ->findOneBySlug($product_slug);

        if ($product === null) {
            $str = sprintf('No product matched "%s"',$product_slug);
            throw $this->createNotFoundException($str);
        }

        return $this->container->get('templating')->renderResponse('MainBundle:Default:show.html.twig', array(
            'product' => $product
        ));
    }

    public function categoryAction($category_name)
    {
        // $em = $this->
    }

    public function editProductAction($slug, Request $request)
    {
        $product = new Product(); // Would actually do a lookup
        //$createProductAction($product);
        return $this->doProductStuff($request,$product);
    }

    public function createProductAction(Request $request)
    {
        $product = new Product();
        return $this->doProductStuff($product,$request);
    }

    protected function doProductStuff(Product $product, Request $request)
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        
        
        $form = $this->container->get('form.factory')->createBuilder('form', $product)
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
                
                $url = $this->container->get('router')->generate('homepage');
                $resp = new RedirectResponse($url);
                                $this->get('session');
                
                $this->container->get('session')
                    ->setFlash('notice', 'Product saved!');
                                
                return $resp;
            
            }
        }
        
        return $this->container->get('templating')->renderResponse('MainBundle:Default:create.html.twig', array(
            'form' => $form->createView()
        ));

    }

    public function reportAction()
    {
        $manager = $this->container->get('report_manager');
        
        return new Response($manager->generate());
    }
    
}
