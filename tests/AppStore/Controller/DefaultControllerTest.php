<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Functional test that implements a "smoke test" of all the public and secure
 * URLs of the application.
 * See https://symfony.com/doc/current/best_practices/tests.html#functional-tests.
 *
 * Execute the application tests using this command (requires PHPUnit to be installed):
 *
 *     $ cd your-symfony-project/
 *     $ ./vendor/bin/phpunit
 */
class DefaultControllerTest extends WebTestCase
{

    /**
     * PHPUnit's data providers allow to execute the same tests repeated times
     * using a different set of data each time.
     * See https://symfony.com/doc/current/cookbook/form/unit_testing.html#testing-against-different-sets-of-data.
     *
     * @dataProvider getPublicUrls
     */
    public function testPublicUrls($url)
    {
        $client = static::createClient();
        $client->request('GET', $url);

        $this->assertSame(
            Response::HTTP_OK,
            $client->getResponse()->getStatusCode(),
            sprintf('The %s public URL loads correctly.', $url)
        );
    }

    /**
     * A good practice for tests is to not use the service container, to make
     * them more robust. However, in this example we must access to the container
     * to get the entity manager and make a database query. The reason is that
     * blog post fixtures are randomly generated and there's no guarantee that
     * a given blog post slug will be available.
     */
    public function testPublicBlogPost()
    {
        $client = static::createClient();
        // the service container is always available via the test client
        $blogPost = $client->getContainer()->get('doctrine')->getRepository(Post::class)->find(1);
        $client->request('GET', sprintf('/en/blog/posts/%s', $blogPost->getSlug()));

        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

}
