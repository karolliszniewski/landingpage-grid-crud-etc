<?php

namespace LandingPage\Form\Model;

use Magento\Framework\Model\AbstractModel;
use  LandingPage\Form\Api\Data\FormDataInterface;
use Magento\Framework\DataObject\IdentityInterface;




class FormData extends AbstractModel implements FormDataInterface, IdentityInterface
{
    protected function _construct()
    {
        $this->_init(\LandingPage\Form\Model\ResourceModel\FormData::class);
    }

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities() : array
    {
        return [self::CACHE_TAG . '_' . $this->getId(), self::CACHE_TAG . '_' . $this->getIdentifier()];
    }


    /**
     * ID
     *
     * @return int
     */
    public function getId() : int
    {
        return (bool)$this->getData('id');
    }

    /**
     * Set ID
     *
     * @param int $id
     * @return FormDataInterface
     */
    public function setId($id) : FormDataInterface
    {
        return $this->setData('id', $id);
    }

     /**
     * Customer ID
     *
     * @return int
     */
    public function getByCustomerId($customerId) {


        
        $collection = $this->formDataCollectionFactory->create();
     
        $collection->addFieldToFilter('customer_id', $customerId);
    
        return $collection->getSize() > 0; // Returns true if records exist, false otherwise
    }

    /**
     * Set Customer ID
     *
     * @param int $customerId
     * @return FormDataInterface
     */
    public function setCustomerId($customerId) : FormDataInterface
    {
        return $this->setData('customer_id', $customerId);
    }

     /**
     * Comment
     *
     * @return string
     */
    public function getComment() : string
    {
        return $this->getData('comment');
    }

    /**
     * Set Customer ID
     *
     * @param string $comment
     * @return FormDataInterface
     */
    public function setComment($comment) : FormDataInterface
    {
        return $this->setData('comment', $comment);
    }

}
