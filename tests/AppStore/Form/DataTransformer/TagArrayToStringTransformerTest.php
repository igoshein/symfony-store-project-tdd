<?php

namespace Tests\AppBundle\Form\DataTransformer;

use AppBundle\Entity\Tag;
use AppBundle\Form\DataTransformer\TagArrayToStringTransformer;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;

/**
 * Tests that tags are transformed correctly using the data transformer.
 *
 * See https://symfony.com/doc/current/testing/database.html
 */
class TagArrayToStringTransformerTest extends \PHPUnit\Framework\TestCase
{

    /**
     * Ensures that tags are created correctly.
     */
    public function testCreateTheRightAmountOfTags()
    {
        $tags = $this->getMockedTransformer()->reverseTransform('Hello, Demo, How');

        $this->assertCount(3, $tags);
        $this->assertSame('Hello', $tags[0]->getName());
    }

}
