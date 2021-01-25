<?php

namespace App\Command;

use App\Entity\PictureSimilarity;
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
            // Get the first 1000 duplicated products
            $getDuplicatedProductSql = "SELECT product_id, COUNT(*) AS duplicate_count
                FROM picture_similarity
                GROUP BY product_id
                HAVING duplicate_count > 1
                LIMIT 0, 1000";
            $duplicatedProducts = $conn->executeQuery($getDuplicatedProductSql)->fetchAllAssociative();

            // Delete all products that have id != [latest id] and product_id = [latest product_id]
            $pictureSimilarityRepository = $this->entityManager->getRepository(PictureSimilarity::class);
            foreach ($duplicatedProducts as $duplicatedProduct) {
                // Get the latest product of the duplicated product
                $latestProduct = $pictureSimilarityRepository->findBy([
                    'productId' => $duplicatedProduct['product_id'],
                ], ['updatedAt' => 'DESC'], 1)[0];

                $deleteDuplicatedProductSQL = "DELETE FROM picture_similarity 
                        WHERE id != {$latestProduct->getId()} 
                            AND product_id = '{$latestProduct->getProductId()}'";
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