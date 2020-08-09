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

    protected $_objectManager;

    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager
    )
    {
        $this->_objectManager = $objectManager;
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
     * @param        $host
     * @param        $port
     * @param string $protocol
     * @return TcpTransport|UdpTransport|null
     */
    public function getTransport($host, $port, $protocol = GraylogProtocol::TCP_VALUE)
    {
        $transport = null;
        if (!empty($host) && !empty($port)) {
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
     * @param        $host
     * @param        $port
     * @param string $protocol
     * @return Publisher|null
     */
    public function getPublisher($host, $port, $protocol = GraylogProtocol::TCP_VALUE)
    {
        $publisher = null;
        $transport = $this->getTransport($host, $port, $protocol);
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
     * @param        $host
     * @param        $port
     * @param string $protocol
     * @param string $facility
     * @param array  $defaultContext
     * @return Logger
     */
    public function build(
        $host,
        $port,
        $protocol = GraylogProtocol::TCP_VALUE,
        $facility = '',
        $defaultContext = array()
    )
    {
        $handler = null;
        try {
            $publisher = $this->getPublisher($host, $port, $protocol);
            if ($publisher) {
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
        return $handler;
    }
}