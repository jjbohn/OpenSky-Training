<?php

namespace Train\MainBundle\Tests;

use Train\MainBundle\ReportManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ReportManagerTest extends WebTestCase
{
    private $productRepo;
    private $templating;
    private $logger;

    protected function setUp()
    {
        $this->productRepo = 
            $this->getMockBuilder('Train\MainBundle\Entity\ProductRepository')
            ->disableOriginalConstructor()
            ->getMock();

        $this->templating = 
            $this->getMockBuilder('Symfony\Component\Templating\EngineInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->logger = 
            $this->getMockBuilder('Monolog\Loger')
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testGenerateCallsRepoFindAll()
    {
        $this->productRepo->expects($this->once())
            ->method('findAll');
        $manager = new ReportManager($this->productRepo,$this->templating);
        $manager->generate();
    }

    public function testGeneratedReportHasData()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/report');

        $this->assertTrue($crawler->filter('tr')->count() > 1);
    }

    public function testGenerateReportWithLog()
    {
        $this->markTestIncomplete('Not implemented yet...');
    }
}
