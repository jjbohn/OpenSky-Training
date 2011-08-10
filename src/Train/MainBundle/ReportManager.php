<?php

namespace Train\MainBundle;

use Train\MainBundle\Entity\ProductRepository;
use Symfony\Component\Templating\EngineInterface as TemplatingEngine;
use Monolog\Logger;

class ReportManager
{
    private $repo;
    private $templating;
    private $logger;
    
    public function __construct(ProductRepository $repo, TemplatingEngine $templating)
    {
        $this->repo = $repo;
        $this->templating = $templating;
    }
    
    public function setLogger(Logger $logger = null)
    {
        $this->logger = $logger;
    }
    
    public function generate()
    {
        $products = $this->repo->findAll();
        
        if ( isset($this->logger) ) {
            $this->logger->addDebug('Made report for products.', array('products' => $products));
        }
        
        return $this->templating->render('MainBundle:Default:report.html.twig', array(
           'products' => $products 
        ));
    }
}
