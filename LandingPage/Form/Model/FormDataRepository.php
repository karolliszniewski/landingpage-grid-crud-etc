<?php
namespace LandingPage\Form\Model;

use LandingPage\Form\Api\FormDataRepositoryInterface;
use LandingPage\Form\Api\Data\FormDataInterface;
use LandingPage\Form\Model\ResourceModel\FormData as FormDataResource;
use LandingPage\Form\Model\ResourceModel\FormData\CollectionFactory as FormDataCollectionFactory;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\CouldNotDeleteException;
use LandingPage\Form\Model\FormDataFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchResultsFactory;

class FormDataRepository implements FormDataRepositoryInterface
{
    protected $resource;
    protected $collectionFactory;
    protected $collectionProcessor;
    protected $searchResultsFactory;

    public function __construct(
        FormDataResource $resource, 
        FormDataCollectionFactory $collectionFactory,
        FormDataFactory $formDataFactory,
        CollectionProcessorInterface $collectionProcessor,
        SearchResultsFactory $searchResultsFactory
        )
    {
        $this->resource = $resource;
        $this->collectionFactory = $collectionFactory;
        $this->formDataFactory = $formDataFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->searchResultsFactory = $searchResultsFactory;
    }

    public function save(FormDataInterface $formData)
    {
   
        try {

            $this->resource->save($formData);
        } catch (\Exception $exception) {
       
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $formData;
    }

    /**
     * Load Accordion data by given Id
     *
     * @param string $id
     * @return FormData
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($id) : FormData
    {
        $formData = $this->formDataFactory->create();
        $this->resource->load($formData, $id);
        if (!$formData->getId()) {
            throw new NoSuchEntityException(__('The product accordion with the "%1" ID doesn\'t exist.', $id));
        }
        return $formData;
    }

    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $criteria)
    {
        $collection = $this->collectionFactory->create();

        $this->collectionProcessor->process($criteria, $collection);

        /** @var $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    public function delete(FormDataInterface $formData)
    {
        try {
            $this->resource->delete($formData);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }
}
