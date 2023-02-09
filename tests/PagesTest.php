<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PagesTest extends WebTestCase
{
    public function testHomePage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
            
        $this->assertResponseIsSuccessful();
        $this->assertPageTitleContains('CMI Test');
        $this->assertSelectorExists('h1');
        $this->assertSelectorTextContains('#first_block_title', 'Les derniers commentaires');
        $this->assertSelectorTextContains('#second_block_title', 'Trouvez des articles intéressants');
    }

    public function testCommentPage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/post/1');
            
        $this->assertResponseIsSuccessful();
        $this->assertPageTitleContains('CMI Test');
        $this->assertSelectorTextContains('#article_title', 'Article : ');
    }
}
