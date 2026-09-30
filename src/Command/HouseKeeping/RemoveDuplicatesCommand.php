<?php

namespace Makaira\PictureSimilarity\Command\HouseKeeping;

use Doctrine\DBAL\Exception as DBALException;
use Doctrine\ORM\EntityManagerInterface;
use Makaira\PictureSimilarity\Entity\PictureSimilarity;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'house-keeping:remove-duplicates',
    description: 'Remove duplicate records',
    aliases: ['app:clean-duplicated-data'],
)]
class RemoveDuplicatesCommand extends Command
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
                $findResult = $pictureSimilarityRepository->findBy(
                    ['productId' => $duplicatedProduct['product_id']],
                    ['updatedAt' => 'DESC'],
                    1
                )[0];

                $latestProduct = reset($findResult);

                $deleteDuplicatedProductSQL = "DELETE FROM picture_similarity WHERE id != ? AND product_id = ?";
                $conn->executeQuery(
                    $deleteDuplicatedProductSQL,
                    [$latestProduct->getId(), $latestProduct->getProductId()]
                );
            }

            $output->write('Command executed successfully!');
            return Command::SUCCESS;
        } catch (DBALException $e) {
            $output->write($e->getMessage());
            return Command::FAILURE;
        }
    }
}
