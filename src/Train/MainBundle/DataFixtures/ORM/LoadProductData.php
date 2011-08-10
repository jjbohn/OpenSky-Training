<?php

namespace Train\MainBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Train\MainBundle\Entity\Product;
use Train\MainBundle\Entity\Category;
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
        $category_a = new Category();
        $category_a->setName('Category a');
        $category_b = new Category();
        $category_b->setName('Category b');

        $em->persist($category_a);
        $em->persist($category_b);

        $product_a = new Product();
        $product_a->setDescription('My first test product.');
        $product_a->setName('T-Shirt 1');
        $product_a->setPrice(50);

        $product_a->setCategory($category_a);
        
        $product_b = new Product();
        $product_b->setDescription('Some awesome mushrooms!');
        $product_b->setName('Bag of fancy Mushrooms!');
        $product_b->setPrice(60);

        $product_b->setCategory($category_b);        
        
        $product_c = new Product();
        $product_c->setDescription('A super expensive spoon.');
        $product_c->setName('T-Shirt 1');
        $product_c->setPrice(120);

        $product_c->setCategory($category_a);

        $em->persist($product_a);
        $em->persist($product_b);
        $em->persist($product_c);
        $em->flush();
    }
}