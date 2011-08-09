<?php

namespace Train\MainBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Train\MainBundle\Entity\Product;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadProductData implements FixtureInterface, ContainerAwareInterface
{
    private $container;
    
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
    
    public function load($em)
    {
        $product_a = new Product();
        $product_a->setDescription('My first test product.');
        $product_a->setName('T-Shirt 1');
        $product_a->setPrice(50);     
        
        $product_b = new Product();
        $product_b->setDescription('Some awesome mushrooms!');
        $product_b->setName('Bag of fancy Mushrooms!');
        $product_b->setPrice(60);
        
        $product_c = new Product();
        $product_c->setDescription('A super expensive sppon.');
        $product_c->setName('T-Shirt 1');
        $product_c->setPrice(120);
        
        $em->persist($product_a);
        $em->persist($product_b);
        $em->persist($product_c);
        $em->flush();
    }
}