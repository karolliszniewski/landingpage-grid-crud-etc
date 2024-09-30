<?php

namespace LandingPage\Form\Model\ResourceModel\FormData;

use LandingPage\Form\Api\Data\FormDataInterface;
use \Magento\Cms\Model\ResourceModel\AbstractCollection;

/**
 * ProductAccordion Collection
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'id';

    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'formlanding_form_collection';

    /**
     * Event object
     *
     * @var string
     */
    protected $_eventObject = 'formlanding_form_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct() : void
    {
        $this->_init(\LandingPage\Form\Model\FormData::class, \LandingPage\Form\Model\ResourceModel\FormData::class);
    }

     /**
     * Add filter by store
     *
     * @param int|array|\Magento\Store\Model\Store $store
     * @param bool $withAdmin
     * @return $this
     */
    public function addStoreFilter($store, $withAdmin = true)
    {
        $this->performAddStoreFilter($store, $withAdmin);

        return $this;
    }
}
