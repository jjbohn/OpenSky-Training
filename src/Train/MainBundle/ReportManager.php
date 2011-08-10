<?php

namespace Train\MainBundle;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Templating\EngineInterface as TemplatingEngine;
use Monolog\Logger;

class ReportManager
{
    private $em;
    private $templating;
    private $logger;
    
    public function __construct(EntityManager $em, TemplatingEngine $templating)
    {
        $this->em = $em;
        $this->templating = $templating;
    }
    
    public function setLogger(Logger $logger = null)
    {
        $this->logger = $logger;
    }
    
    public function generate()
    {
        $products = $this->em->getRepository('MainBundle:Product')->findAll();
        
        if ( isset($this->logger) ) {
            $this->logger->addDebug('Made report for products.', array('products' => $products));
        }
        
        return $this->templating->render('MainBundle:Default:report.html.twig', array(
           'products' => $products 
        ));
    }
}