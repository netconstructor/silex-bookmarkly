<?php
namespace App\AppTest;

use Silex\WebTestCase;

class AppTest extends WebTestCase{
    function createApplication(){
        putenv("MARKME_DB_DRIVER=pdo_sqlite");
        $schema = file_get_contents(__DIR__.'/../../Database/schema.sqlite.sql');
        $app = require __DIR__.'/../../App/application.php';
        $app["debug"] = true;
        $app["exception_handler"]->disable();
        $app["session.test"] = true;
        $statement = $app["db"]->prepare($schema);
        $statement->execute();
        return $app;
    }

    /**
     * Affiche la page de garde du site internet
     */
    function testIndex(){
        $client = $this->createClient();
        $crawler = $client->request("GET","/");

        $this->assertTrue($client->getResponse()->isOk());
        $this->assertCount(1,$crawler->filter("h1.title:contains('Hello Silex !')"));
        $this->assertCount(1,$crawler->filter("body"));

        $crawler = $client->request("GET","/Marc");
        $this->assertCount(1,$crawler->filter("h1.title:contains('Hello Marc !')"));

        $crawler = $client->request("GET","/Marc Prades");
        $this->assertCount(1,$crawler->filter("h1.title:contains('Hello Marc Prades !')"));

    }

    /**
     * crée un utilisateur via une requète json
     */
    function testJsonRegister_POST(){
        $this->assertTrue(true);
    }

    function testTest(){
        $this->assertEquals(true,true);
    }
}

