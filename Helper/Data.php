<?php
/**
 * Copyright © Hieu Le All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Hidro\Graylog\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

class Data extends AbstractHelper
{
    const XML_GRAYLOG_CONFIG_PREFIX = 'system/graylog/';
    /**
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context
    ) {
        parent::__construct($context);
    }

    public function getConfig($path){
        return $this->scopeConfig->getValue(self::XML_GRAYLOG_CONFIG_PREFIX . $path, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return !!$this->getConfig('enabled');
    }

    public function getServerHost(){
        return $this->getConfig('host');
    }

    public function getServerPort(){
        return $this->getConfig('port');
    }
}

