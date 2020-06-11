<?php
/**
 * Created by Hidro Le.
 * Job Title: Magento Developer
 * Project Name: M2.3.0 - CE - Default Magento
 * Date: 10/06/2020
 * Time: 23:55
 */

namespace Hidro\Graylog\Logger\GrayLog;

use Gelf\Publisher;
use Gelf\Transport\UdpTransport;
use Gelf\Logger;

class LoggerBuilder
{

    /**
     * Object manager
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $_objectManager;

    /**
     * @var \Hidro\Graylog\Helper\Data
     */
    private $_graylogHelper;

    protected $_publisher;


    public function __construct(
        \Hidro\Graylog\Helper\Data $graylogHelper,
        \Magento\Framework\ObjectManagerInterface $objectManager
    )
    {
        $this->_objectManager = $objectManager;
        $this->_graylogHelper = $graylogHelper;
    }

    protected function getUdpTransport(){
        return $this->_objectManager->create(UdpTransport::class, [
            'host' => $this->_graylogHelper->getServerHost(),
            'port' => $this->_graylogHelper->getServerPort(),
            UdpTransport::CHUNK_SIZE_LAN
        ]);
    }

    protected function getPublisher(){
        if(!$this->_publisher) {
            $transport = $this->getUdpTransport();
            /**
             * @var $publisher Publisher
             */
            $publisher = $this->_objectManager->get(Publisher::class);
            $publisher->addTransport($transport);
            $this->_publisher = $publisher;
        }
        return $this->_publisher;
    }

    /**
     * @param $facility
     * @return Logger
     */
    public function prepareHandler($facility){
        if($this->_graylogHelper->isEnabled()) {
            $publisher = $this->getPublisher();
            $handler = $this->_objectManager->create(Logger::class, [
                'publisher' => $publisher,
                'facility' => $facility
            ]);
            return $handler;
        }
        return null;
    }
}