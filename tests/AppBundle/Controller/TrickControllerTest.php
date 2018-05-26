<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 25/05/2018
 * Time: 23:52
 */

namespace Tests\AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class TrickControllerTest extends WebTestCase
{
    const CATEGORY_GRABS_ID = 64;
    const CATEGORY_FLIPS_ID = 66;
    private $client = null;

    public function setUp()
    {
        parent::setUp();
        $this->client = static::createClient(array(), array());
    }

    public function testSimpleAddTrick()
    {
        $this->logIn();

        $crawler = $this->client->request('POST', '/trick/add');
        $form = $crawler->selectButton('Save')->form(array(
            'snowtricks_appbundle_trick[name]'      => 'Trick Name',
            'snowtricks_appbundle_trick[description]'      => 'A very very very long long long long description.',
            'snowtricks_appbundle_trick[category]'      => $this::CATEGORY_GRABS_ID
        ));

        $crawler = $this->client->submit($form);

    }

    private function logIn()
    {
        $session = $this->client->getContainer()->get('session');

        $firewallName = 'main';
        // if you don't define multiple connected firewalls, the context defaults to the firewall name
        // See https://symfony.com/doc/current/reference/configuration/security.html#firewall-context
        $firewallContext = 'main';

        $token = new UsernamePasswordToken('user', null, $firewallName, array('ROLE_USER'));
        $session->set('_security_'.$firewallContext, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }


    public function testSimpleEditTRick()
    {
        $this->logIn();

        $crawler = $this->client->request('POST', '/trick/edit/116');
        $form = $crawler->selectButton('Save')->form(array(
            'snowtricks_appbundle_trick[name]'      => 'Trick Name Edited !',
            'snowtricks_appbundle_trick[description]'      => 'Edit Description !',
            'snowtricks_appbundle_trick[category]'      => $this::CATEGORY_FLIPS_ID
        ));

        $crawler = $this->client->submit($form);

    }

    public function testViewTrick()
    {

        $crawler = $this->client->request('GET', '/trick/116/japan-air');
        $this->assertTrue($this->client->getResponse()->isSuccessful(), 'response status is 2xx');

    }

    public function testHome()
    {
        $crawler = $this->client->request('GET', '/');
        $this->assertTrue($this->client->getResponse()->isSuccessful(), 'response status is 2xx');
    }

}
