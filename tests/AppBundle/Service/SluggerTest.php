<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 24/05/2018
 * Time: 22:53
 */

namespace Tests\AppBundle\Service;

use PHPUnit\Framework\TestCase;
use SnowTricks\AppBundle\Service\Slugger;


class SluggerTest extends TestCase
{

    private $slugger;

    public function setUp()
    {
        $this->slugger = new Slugger();
    }

    public function testSlugifySimple()
    {
        $this->assertEquals('hello-world', $this->slugger->slugify('Hello World'));
    }

    public function testSlugifyWithSpaces()
    {
        $this->assertEquals('hello-world', $this->slugger->slugify('      Hello         World         '));
    }

    public function testSlugifyWithQuotes()
    {
        $this->assertEquals('event-law', $this->slugger->slugify("'Event law'"));
    }

    public function testSlugifySpecialCharacters()
    {
        $this->assertEquals('eau', $this->slugger->slugify("éàù?!"));
    }

}
