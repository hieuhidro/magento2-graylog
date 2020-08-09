<?php
/**
 * Created by Hidro Le.
 * Job Title: Magento Developer
 * Project Name: m2cedefault.local
 * Date: 6/20/20
 * Time: 00:05
 */

namespace Hidro\Graylog\Model\Config\Source;

class Protocol implements \Magento\Framework\Option\ArrayInterface
{

    /**
     * Transmission Control Protocol.
     */
    const TCP_VALUE = 'tcp';

    /**
     * User Datagram Protocol.
     */
    const UDP_VALUE = 'udp';

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
//            ['value' => self::TCP_VALUE, 'label' => __('TCP')],
            ['value' => self::UDP_VALUE, 'label' => __('UDP')],
        ];
    }
}
