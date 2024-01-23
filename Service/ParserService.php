<?php

declare(strict_types=1);

namespace Jenyamba\ParseXml\Service;

use Exception;
use Magento\Framework\Xml\Parser;
use Jenyamba\ParseXml\Logger\Logger;

class ParserService
{
    const FILE_PATH = 'var/import/';

    /**
     * @var Parser
     */
    private Parser $parser;

    /**
     * @var Logger
     */
    private Logger $logger;

    /**
     * @param Parser $parser
     * @param Logger $logger
     */
    public function __construct(
        Parser $parser,
        Logger $logger
    ) {
        $this->logger = $logger;
        $this->parser = $parser;
    }

    /**
     * @return array
     */
    public function getArrayWithDataFromXMLFile(): array
    {
        $allFiles = scandir(self::FILE_PATH);
        foreach ($allFiles as $fileName) {
            $pathInfo = pathinfo($fileName);
            try {
                if (isset($pathInfo['extension']) && $pathInfo['extension'] == 'xml') {
                    $parsedArray = $this->parser->load(self::FILE_PATH . $fileName)->xmlToArray();
                }
            } catch (Exception $exception) {
                $this->logger->error("XML file with order data doesn`t exists");
            }
        }
        return $parsedArray ?? [];
    }
}
