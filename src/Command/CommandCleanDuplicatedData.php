<?php

namespace Makaira\PictureSimilarity\Command;

use Makaira\PictureSimilarity\Entity\PictureSimilarity;
use Doctrine\DBAL\Driver\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:clean-duplicated-data')]
class CommandCleanDuplicatedData extends Command
{
    public function __construct(protected EntityManagerInterface $entityManager)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
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
            return Command::SUCCESS;
        } catch (\Exception | Exception $e) {
            $output->write($e->getMessage());
            return Command::FAILURE;
        }
    }
}
