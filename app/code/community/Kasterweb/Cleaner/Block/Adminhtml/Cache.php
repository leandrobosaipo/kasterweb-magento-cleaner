<?php

class Kasterweb_Cleaner_Block_Adminhtml_Cache extends Mage_Adminhtml_Block_Template
{
    public function getMageTablesUrl()
    {
        return $this->getUrl('*/*/mageTables');
    }

    public function getMageDirUrl()
    {
        return $this->getUrl('*/*/mageDir');
    }

    public function getRedisUrl()
    {
        return $this->getUrl('*/*/redis');
    }

    public function getMemcachedUrl()
    {
        return $this->getUrl('*/*/memcached');
    }

    public function getCdnUrl()
    {
        return $this->getUrl('*/*/cdn');
    }
}
