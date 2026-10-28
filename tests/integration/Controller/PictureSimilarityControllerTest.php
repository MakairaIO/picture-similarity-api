<?php

namespace Makaira\PictureSimilarity\Tests\Integration\Controller;

use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use function json_decode;

use const JSON_THROW_ON_ERROR;

class PictureSimilarityControllerTest extends WebTestCase
{
    public static function provideRequestData(): Generator
    {
        yield 'Single product without type' => [
            '/testshop_test/1',
            200,
            ["image", "type", "product", 1]
        ];
        yield 'Single product with type' => [
            '/notImage/testshop_test/1',
            200,
            ["notImage", "type", "product", 1]
        ];

        yield 'Multiple products without type' => [
            '/testshop_test/products/1,2,3',
            200,
            ["image", "type", "product", 1, 2, 3],
        ];
        yield 'Multiple products with type' => [
            '/notImage/testshop_test/products/1,2,3',
            200,
            ["notImage", "type", "product", 1, 2, 3],
        ];

        yield 'Invalid product without type' => [
            '/testshop_test/non_existent_id',
            404,
            ['message' => 'No Similar Products found.', 'code' => 0],
        ];
        yield 'Invalid product with type' => [
            '/notImage/testshop_test/non_existent_id',
            404,
            ['message' => 'No Similar Products found.', 'code' => 0],
        ];

        yield 'Invalid products without type' => [
            '/testshop_test/products/non_existent_id_1,non_existent_id_2,non_existent_id_3',
            404,
            ['message' => 'No Similar Products found.', 'code' => 0],
        ];
        yield 'Invalid products with type' => [
            '/notImage/testshop_test/products/non_existent_id_1,non_existent_id_2,non_existent_id_3',
            404,
            ['message' => 'No Similar Products found.', 'code' => 0],
        ];
    }

    #[DataProvider(methodName: "provideRequestData")]
    public function testFindBy(string $path, int $expectedStatus, array $expectedResponse): void
    {
        $client = static::createClient();

        $client->request('GET', sprintf('/api%s', $path));
        self::assertResponseStatusCodeSame($expectedStatus);

        $actualResponse = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $this->assertEquals($expectedResponse, $actualResponse);
    }

    public function testGetAvailableTypes(): void
    {
        $client = static::createClient();

        // Test without type
        $client->request('GET', '/api/testshop_test/available-types');

        self::assertResponseStatusCodeSame(200);

        $actualResponse = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $expectedResponse = ['image', 'notImage'];
        $this->assertEquals($expectedResponse, $actualResponse);
    }
}
