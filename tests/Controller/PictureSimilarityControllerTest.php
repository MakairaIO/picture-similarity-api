<?php

namespace Makaira\PictureSimilarity\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PictureSimilarityControllerTest extends WebTestCase
{
    public function testFind(): void
    {
        $client = static::createClient();

        // Test without type
        $client->request('GET', '/api/testshop/1');
        $actualResponse = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $expectedResponse = [
            "image",
            "type",
            "product",
            "1"
        ];
        $this->assertEquals($expectedResponse, $actualResponse);

        // Test with type
        $client->request('GET', '/api/notImage/testshop/1');
        $actualResponse = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $expectedResponse = [
            "notImage",
            "type",
            "product",
            "1"
        ];
        $this->assertEquals($expectedResponse, $actualResponse);
    }

    public function testFindByProductIds(): void
    {
        $client = static::createClient();

        // Test without type
        $client->request('GET', '/api/testshop/products/1,2,3');
        $actualResponse = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $expectedResponse = [
            ["image", "type", "product", "1"],
            ["image", "type", "product", "2"],
            ["image", "type", "product", "3"]
        ];
        $this->assertEquals($expectedResponse, $actualResponse);

        // Test with type
        $client->request('GET', '/api/notImage/testshop/products/4,5');
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
        $client->request('GET', '/api/testshop/get-available-types');
        $actualResponse = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $expectedResponse = [
            'image',
            'notImage',
        ];

        $this->assertEquals($expectedResponse, $actualResponse);
    }
}
