<?php

/**
 * Created by Hidro Le.
 * Job Title: Magento Developer
 * Project Name: local.magento2.com
 * Date: 07/08/2020
 * Time: 18:39
 */

namespace Hidro\Graylog\Logger\Handler;

use Hidro\Graylog\Formatter\GelfMessageFormatter;
use Hidro\Graylog\Helper\Configuration;
use Hidro\Graylog\Logger\GraylogBuilder;
use Monolog\Formatter\FormatterInterface;
use Monolog\Logger;

class Graylog extends \Monolog\Handler\GelfHandler
{
    /**
     * @var mixed|string
     */
    protected $graylogBuilder;

    /**
     * @var
     */
    protected $facility;

    /**
     * @var Configuration
     */
    protected $configuration;

    protected $isAllowed = true;

    /**
     * Graylog constructor.
     *
     * @param Configuration  $configuration
     * @param GraylogBuilder $graylogBuilder
     * @param string         $facility
     * @param int            $level
     * @param bool           $bubble
     */
    public function __construct(
        Configuration $configuration,
        GraylogBuilder $graylogBuilder,
        $level = Logger::DEBUG,
        $bubble = true
    ) {
        \Monolog\Handler\AbstractProcessingHandler::__construct($level, $bubble);
        $this->graylogBuilder = $graylogBuilder;
        $this->configuration = $configuration;
    }

    /**
     * Accept all error logs
     * @param array $record
     * @return bool
     */
    public function isHandling(array $record): bool
    {
        if ($this->configuration->isEnabled() && $this->isAllowed) {
            if (!$this->publisher) {
                $this->publisher = $this->graylogBuilder->getPublisher(
                    $this->configuration->getServerHost(),
                    $this->configuration->getServerPort(),
                    $this->configuration->getTransmissionProtocol()
                );
                $this->facility = $this->configuration->getProjectFacility();
            }
            return true;
        }
        return false;
    }

    /**
     * {@inheritDoc}
     */
    protected function getDefaultFormatter(): FormatterInterface
    {
        //Update message channel to facility
        $messageFormatter = new GelfMessageFormatter();
        $messageFormatter->setFacility($this->facility);
        return $messageFormatter;
    }

    /**
     * {@inheritdoc}
     */
    protected function write(array $record): void
    {
        try {
            $this->publisher->publish($record['formatted']);
        } catch (\Exception $e) {
            //Can't connect the hosting
            $this->isAllowed = false;
        }
    }
}
