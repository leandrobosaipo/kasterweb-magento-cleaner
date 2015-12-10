<?php

class Kasterweb_Cleaner_Model_MagentoDatabase extends Mage_Core_Model_Abstract
{
    /**
     * Resource singleton
     *
     * @var \Mage_Core_Model_Resource
     */
    protected $coreResource;

    /**
     * Write connection to DB
     *
     * @var \Varien_Db_Adapter_Interface
     */
    protected $dbWrite;

    /**
     * Read connection to DB
     *
     * @var \Varien_Db_Adapter_Interface
     */
    protected $dbRead;

    /**
     * List of tables in DB
     *
     * @var string[]
     */
    protected $tables;

    /**
     * Resources to truncate
     *
     * @var array
     */
    protected $resources = array(
        // Cache
        'core/cache',
        'core/cache_tag',

        // Catalog
        'catalog_index/aggregation',
        'catalog_index/aggregation/tag',
        'catalog_index/aggregation_to_tag',

        // Session
        'core/session',

        // Dataflow
        'dataflow/batch_export',
        'dataflow/batch_import',

        // Enterprise Admin Logs
        'enterprise_logging/event',
        'enterprise_logging/event_changes',

        // Index
        'index/event',
        'index/process_event',

        // Logs
        'log/customer',
        'log/quote_table',
        'log/summary_table',
        'log/summary_type_table',
        'log/url_table',
        'log/url_info_table',
        'log/visitor',
        'log/visitor_info',
        'log/visitor_online',

        // Reports
        'reports/event',
        'reports/viewed_product_index',
        'reports/viewed_aggregated_daily',
        'reports/viewed_aggregated_monthly',
        'reports/viewed_aggregated_yearly',
    );

    protected function _construct()
    {
        $this->_init('cleaner/magentoDatabase');

        $this->coreResource = Mage::getSingleton('core/resource');
        $this->dbRead = $this->coreResource->getConnection('core_read');
        $this->dbWrite = $this->coreResource->getConnection('core_write');
        $this->tables = $this->getTables();
    }

    public function truncate()
    {
        $truncatedResources = array();
        $this->withoutForeignKeyChecks(array($this, 'truncateResources'));
        return $truncatedResources;
    }

    protected function truncateResources()
    {
        foreach ($this->prepareResources($this->resources) as $resourceTable) {
            $this->dbWrite->truncateTable($resourceTable);
            $truncatedResources[] = $resourceTable;
        }
    }

    protected function withoutForeignKeyChecks($closure)
    {
        $this->setForeignKeyCheck(false);
        $return = call_user_func($closure);
        $this->setForeignKeyCheck(true);
        return $return;
    }

    protected function setForeignKeyCheck($value)
    {
        return $this->dbWrite->query(sprintf('SET foreign_key_checks = %s', (int) $value));
    }

    /**
     * Extract table names and prepare for cli rendering
     *
     * @param array $resources
     *
     * @return void
     */
    protected function prepareResources($resources)
    {
        $preparedResources = array();
        foreach ($resources as $resource) {
            try {
                $preparedResources[] = $this->coreResource->getTableName($resource);
            } catch (Mage_Core_Exception $e) { }
        }
        return $preparedResources;
    }

    /**
     * Get tables in DB
     *
     * @return string[]
     */
    protected function getTables()
    {
        $stmt = $this->dbRead->query('SHOW TABLES');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
