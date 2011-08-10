<?php

namespace Train\MainBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use FOS\UserBundle\Entity\User;
use FOS\UserBundle\Util\UserManipulator;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUserData implements FixtureInterface, ContainerAwareInterface
{
    private $container;
    
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load($em)
    {
        $um = $this->container->get('fos_user.util.user_manipulator');
	$dave = $um->create('Dave', 'password', 'dave@test.com', true, false);
        $em->persist($dave);
        $admin = $um->create('Admin', 'password', 'admin@test.com', true, false);
        $em->persist($admin);
        $em->flush();
    }
}
