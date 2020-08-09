<?php
/**
 * Copyright © Hieu Le All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Hidro\Graylog\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Test extends Command
{

    const MESSAGE_ARGUMENT = "message";
    protected $logger;

    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        string $name = null)
    {
        parent::__construct($name);
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ) {
        $message = $input->getArgument(self::MESSAGE_ARGUMENT)?:__("Push test data to gray log server");
        $this->logger->critical($message);
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName("magento_graylog:test");
        $this->setDescription("Push test data to gray log server.");
        $this->setDefinition([
            new InputArgument(self::MESSAGE_ARGUMENT, InputArgument::OPTIONAL, "Message"),
        ]);
        parent::configure();
    }
}

