<?php
/**
 * Created by Hidro Le.
 * Job Title: Magento Developer
 * Project Name: m2cedefault.local
 * Date: 8/9/20
 * Time: 14:38
 */


namespace Hidro\Graylog\Logger;


use Gelf\Logger;
use Gelf\Publisher;
use Gelf\Transport\TcpTransport;
use Gelf\Transport\UdpTransport;
use Hidro\Graylog\Model\Config\Source\Protocol as GraylogProtocol;

class GraylogBuilder
{
    protected $_configuration;

    protected $_objectManager;

    protected $_urlBuilder;

    public function __construct(
        \Hidro\Graylog\Helper\Configuration $configuration,
        \Magento\Framework\UrlInterface $urlBuilder,
        \Magento\Framework\ObjectManagerInterface $objectManager
    )
    {
        $this->_objectManager = $objectManager;
        $this->_urlBuilder = $urlBuilder;
        $this->_configuration = $configuration;
    }

    public function getFacility(){
        return $this->_configuration->getProjectFacility();
    }

    /**
     * @param $host
     * @param $port
     * @return \Gelf\Transport\UdpTransport
     */
    protected function getUdpTransport($host, $port)
    {
        return $this->_objectManager->create(UdpTransport::class, [
            'host' => $host,
            'port' => $port,
            UdpTransport::CHUNK_SIZE_LAN
        ]);
    }

    /**
     * @param string $host
     * @param integer $port
     * @param \Gelf\Transport\SslOptions $sslOptions
     * @return \Gelf\Transport\TcpTransport
     */
    protected function getTcpTransport($host, $port, $sslOptions = null)
    {
        return $this->_objectManager->create(TcpTransport::class, [
            'host' => $host,
            'port' => $port,
            'sslOptions' => $sslOptions
        ]);
    }

    /**
     * @return TcpTransport|UdpTransport|null
     */
    public function getTransport()
    {
        $host = $this->_configuration->getServerHost();
        $port = $this->_configuration->getServerPort();
        $transport = null;
        if (!empty($host) && !empty($port)) {
            $protocol = $this->getTransmissionProtocol();
            switch ($protocol) {
                case GraylogProtocol::TCP_VALUE :
                    $transport = $this->getTcpTransport($host, $port);
                    break;
                default:
                    $transport = $this->getUdpTransport($host, $port);
                    break;
            }
        }
        return $transport;
    }

    /**
     * @return Publisher|null
     */
    public function getPublisher()
    {
        $publisher = null;
        $transport = $this->getTransport();
        if ($transport) {
            /**
             * @var $publisher Publisher
             */
            $publisher = $this->_objectManager->get(Publisher::class);
            $publisher->addTransport($transport);
        }
        return $publisher;
    }

    /**
     * @param $facility
     * @param $defaultContext
     * @return Logger
     */
    public function prepareHandler($facility = '', $defaultContext = array())
    {
        $handler = null;
        if ($this->_configuration->isEnabled()) {
            try {
                $publisher = $this->getPublisher();
                if ($publisher) {
                    if (!$facility) {
                        $facility = $this->_configuration->getProjectFacility();
                    }
                    $handler = $this->_objectManager->create(Logger::class, [
                        'publisher' => $publisher,
                        'facility' => $facility,
                        'defaultContext' => $defaultContext
                    ]);
                }
            } catch (\Exception $e) {
                //Ignore exception.
                $handler = null;
            }
        }
        return $handler;
    }
}