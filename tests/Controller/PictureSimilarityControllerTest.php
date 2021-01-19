<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PictureSimilarityControllerTest extends WebTestCase
{
    public function testFind()
    {
        $client = static::createClient();
        $expectedResponse = [
            "its",
            "the",
            "pingdom",
            "test"
        ];

        // Test without type
        $client->request('GET', '/api/testshop/testproduct');
        $actualResponse = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals($expectedResponse, $actualResponse);

        // Test with type
        $client->request('GET', '/api/image/testshop/testproduct');
        $actualResponse = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals($expectedResponse, $actualResponse);
    }

    public function testFindByProductIds()
    {
        $client = static::createClient();
        $expectedResponse = [
            [
                "its",
                "the",
                "pingdom",
                "test"
            ]
        ];

        // Test without type
        $client->request('GET', '/api/testshop/products/testproduct');
        $actualResponse = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals($expectedResponse, $actualResponse);

        // Test with type
        $client->request('GET', '/api/image/testshop/products/testproduct');
        $actualResponse = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals($expectedResponse, $actualResponse);
    }
}