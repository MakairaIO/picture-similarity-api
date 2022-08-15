<?php

namespace Makaira\PictureSimilarity\Doctrine\DBAL;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

final class MultiDbConnectionWrapper extends Connection
{
    use DatabaseNameNormalizerTrait;

    /**
     * @param string $dbName
     *
     * @return void
     * @throws Exception
     */
    public function selectDatabase(string $dbName): void
    {
        if ($this->isConnected()) {
            $this->close();
        }

        $params           = $this->getParams();
        $params['dbname'] = $this->normalize($dbName);
        $this->__construct($params, $this->_driver, $this->_config, $this->_eventManager);
    }
}
