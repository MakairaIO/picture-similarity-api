<?php

namespace App\Command;

use Doctrine\DBAL\Driver\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CommandCleanDuplicatedData extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:clean-duplicated-data';
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }


    protected function execute(InputInterface $input, OutputInterface $output): ?int
    {
        try {
            $conn = $this->entityManager->getConnection();
            // Get latest products
            $getLatestProductSql = "SELECT ps.id, ps.product_id, ps.shop, ps.type
            FROM picture_similarity AS ps 
            JOIN (SELECT product_id, shop, type, MAX(updated_at) updated_at FROM picture_similarity GROUP BY product_id, shop, type) AS sps
            ON sps.updated_at = ps.updated_at
                AND sps.product_id = ps.product_id
                AND sps.shop = ps.shop
                AND sps.type = ps.type
            limit 0, 1000";
            $queryResult = $conn->executeQuery($getLatestProductSql);
            $latestProducts = $queryResult->fetchAllAssociative();

            // Delete all products that have id != [latest id] and product_id = [latest product_id] and shop = [latest shop] and type = [latest type]
            foreach ($latestProducts as $latestProduct) {
                $deleteDuplicatedProductSQL = "DELETE FROM picture_similarity
                WHERE id != {$latestProduct['id']}
                    AND product_id = '{$latestProduct['product_id']}'
                    AND shop = '{$latestProduct['shop']}'
                    AND type = '{$latestProduct['type']}'";
                $conn->executeQuery($deleteDuplicatedProductSQL);
            }
            $output->write('Command executed successfully!');
            return 0;
        } catch (\Exception | Exception $e) {
            $output->write($e->getMessage());
            return 1;
        }
    }
}