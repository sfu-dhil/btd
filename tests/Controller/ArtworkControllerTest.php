<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\DataFixtures\ArtworkFixtures;
use App\Entity\Artwork;
use Nines\UserBundle\DataFixtures\UserFixtures;
use Nines\UtilBundle\TestCase\ControllerTestCase;
use Symfony\Component\HttpFoundation\Response;

class ArtworkControllerTest extends ControllerTestCase {
    public function testAnonIndex() : void {
        $crawler = $this->client->request('GET', '/artwork/');
        $this->assertResponseIsSuccessful();
        $this->assertSame(0, $crawler->selectLink('New')->count());
    }

    public function testUserIndex() : void {
        $this->login(UserFixtures::USER);
        $crawler = $this->client->request('GET', '/artwork/');
        $this->assertResponseIsSuccessful();
        $this->assertSame(0, $crawler->selectLink('New')->count());
    }

    public function testAdminIndex() : void {
        $this->login(UserFixtures::ADMIN);
        $crawler = $this->client->request('GET', '/artwork/');
        $this->assertResponseIsSuccessful();
        $this->assertSame(1, $crawler->selectLink('New')->count());
    }

    public function testAnonShow() : void {
        $crawler = $this->client->request('GET', '/artwork/1');
        $this->assertResponseIsSuccessful();
        $this->assertSame(0, $crawler->selectLink('Edit')->count());
        $this->assertSame(0, $crawler->selectLink('Delete')->count());
    }

    public function testUserShow() : void {
        $this->login(UserFixtures::USER);
        $crawler = $this->client->request('GET', '/artwork/1');
        $this->assertResponseIsSuccessful();
        $this->assertSame(0, $crawler->selectLink('Edit')->count());
        $this->assertSame(0, $crawler->selectLink('Delete')->count());
    }

    public function testAdminShow() : void {
        $this->login(UserFixtures::ADMIN);
        $crawler = $this->client->request('GET', '/artwork/1');
        $this->assertResponseIsSuccessful();
        $this->assertSame(3, $crawler->selectLink('Edit')->count());
        $this->assertSame(1, $crawler->selectLink('Delete')->count());
    }

    public function testAnonEdit() : void {
        $crawler = $this->client->request('GET', '/artwork/1/edit');
        $this->assertResponseRedirects('http://localhost/login', Response::HTTP_FOUND);
    }

    public function testUserEdit() : void {
        $this->login(UserFixtures::USER);
        $crawler = $this->client->request('GET', '/artwork/1/edit');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testAdminEdit() : void {
        $this->login(UserFixtures::ADMIN);
        $formCrawler = $this->client->request('GET', '/artwork/1/edit');
        $this->assertResponseIsSuccessful();

        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
        $form = $formCrawler->selectButton('Update')->form([
            // DO STUFF HERE.
            // 'artworks[FIELDNAME]' => 'FIELDVALUE',
        ]);

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect('/artwork/1'));
        $responseCrawler = $this->client->followRedirect();
        $this->assertResponseIsSuccessful();
        // $this->assertEquals(1, $responseCrawler->filter('td:contains("FIELDVALUE")')->count());
    }

    public function testAnonNew() : void {
        $crawler = $this->client->request('GET', '/artwork/new');
        $this->assertResponseRedirects('http://localhost/login', Response::HTTP_FOUND);
    }

    public function testUserNew() : void {
        $this->login(UserFixtures::USER);
        $crawler = $this->client->request('GET', '/artwork/new');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testAdminNew() : void {
        $this->login(UserFixtures::ADMIN);
        $formCrawler = $this->client->request('GET', '/artwork/new');
        $this->assertResponseIsSuccessful();

        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
        $form = $formCrawler->selectButton('Create')->form([
            // DO STUFF HERE.
            // 'artworks[FIELDNAME]' => 'FIELDVALUE',
        ]);

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());
        $responseCrawler = $this->client->followRedirect();
        $this->assertResponseIsSuccessful();
        // $this->assertEquals(1, $responseCrawler->filter('td:contains("FIELDVALUE")')->count());
    }

    public function testAnonDelete() : void {
        $crawler = $this->client->request('GET', '/artwork/1/delete');
        $this->assertResponseRedirects('http://localhost/login', Response::HTTP_FOUND);
    }

    public function testUserDelete() : void {
        $this->login(UserFixtures::USER);
        $crawler = $this->client->request('GET', '/artwork/1/delete');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testAdminDelete() : void {
        $preCount = count($this->em->getRepository(Artwork::class)->findAll());
        $this->login(UserFixtures::ADMIN);
        $crawler = $this->client->request('GET', '/artwork/1/delete');
        $this->assertResponseRedirects('/artwork/', Response::HTTP_FOUND);
        $responseCrawler = $this->client->followRedirect();
        $this->assertResponseIsSuccessful();

        $this->em->clear();
        $postCount = count($this->em->getRepository(Artwork::class)->findAll());
        $this->assertSame($preCount - 1, $postCount);
    }
}
