<?php

class Kasterweb_Cleaner_Model_Job extends Mage_Core_Model_Abstract
{
    public function performRedis()
    {
        return Mage::getSingleton('cleaner/redis')->truncate();
    }

    public function performMemcached()
    {
        return Mage::getSingleton('cleaner/memcached')->truncate();
    }
}
