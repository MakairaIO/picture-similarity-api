<?php

namespace Makaira\PictureSimilarity\Tests\Integration\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use function json_decode;

use const JSON_THROW_ON_ERROR;

class PictureSimilarityControllerTest extends WebTestCase
{
    public function testFindWithoutType(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/testshop_test/1');
        $actualResponse = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $expectedResponse = [
            "image",
            "type",
            "product",
            "1"
        ];
        $this->assertEquals($expectedResponse, $actualResponse);
    }

    public function testFindWithType(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/notImage/testshop_test/1');
        $actualResponse = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $expectedResponse = [
            "notImage",
            "type",
            "product",
            "1"
        ];
        $this->assertEquals($expectedResponse, $actualResponse);
    }

    public function testFindByProductIdsWithoutType(): void
    {
        $client = static::createClient();

        // Test without type
        $client->request('GET', '/api/testshop_test/products/1,2,3');
        $actualResponse = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $expectedResponse = [
            ["image", "type", "product", "1"],
            ["image", "type", "product", "2"],
            ["image", "type", "product", "3"]
        ];
        $this->assertEquals($expectedResponse, $actualResponse);
    }

    public function testFindByProductIdsWithType(): void
    {
        $client = static::createClient();

        // Test with type
        $client->request('GET', '/api/notImage/testshop_test/products/4,5');
        $actualResponse = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $expectedResponse = [
            ["notImage", "type", "product", "4"],
            ["notImage", "type", "product", "5"],
        ];
        $this->assertEquals($expectedResponse, $actualResponse);
    }

    public function testGetAvailableTypes(): void
    {
        $client = static::createClient();

        // Test without type
        $client->request('GET', '/api/testshop_test/available-types');
        $actualResponse = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $expectedResponse = [
            'image',
            'notImage',
        ];

        $this->assertEquals($expectedResponse, $actualResponse);
    }
}
