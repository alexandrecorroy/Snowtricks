<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 25/05/2018
 * Time: 23:51
 */

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    private $client;

    public function setUp()
    {
        parent::setUp();

        $this->client = static::createClient();
    }

    public function testShowLoginPage()
    {

        $crawler = $this->client->request('GET', '/login');

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Login")')->count()
        );

    }

    public function testHttpResponseLoginPage()
    {
        $crawler = $this->client->request('GET', '/login');
        $this->assertTrue($this->client->getResponse()->isSuccessful(), 'response status is 2xx');
    }


    public function testRedirectLogoutPage()
    {
        $crawler = $this->client->request('GET', '/logout');
        $this->assertTrue($this->client->getResponse()->isRedirect());
    }

    public function testRedirectUserNotConnectedDashboardPage()
    {
        $crawler = $this->client->request('GET', '/dashboard');
        $this->assertTrue($this->client->getResponse()->isRedirect());
    }


}
