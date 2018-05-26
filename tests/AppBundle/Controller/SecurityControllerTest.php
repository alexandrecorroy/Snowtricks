<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 26/05/2018
 * Time: 10:21
 */

namespace Tests\AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{

    private $client;

    public function setUp()
    {
        parent::setUp();
        $this->client = static::createClient();
    }

    public function testRegistration()
    {

        $crawler = $this->client->request('POST', '/registration');
        $form = $crawler->selectButton('Create an account')->form(array(
            'registration[username]'      => 'username',
            'registration[email]'      => 'email@gmail.com',
            'registration[password]'      => 'password'
        ));

        $crawler = $this->client->submit($form);

    }

}
