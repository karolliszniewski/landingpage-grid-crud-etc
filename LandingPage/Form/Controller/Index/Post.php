<?php
namespace LandingPage\Form\Controller\Index;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\Action;
use Magento\Framework\Controller\ResultFactory;
use Psr\Log\LoggerInterface;
use LandingPage\Form\Api\FormDataRepositoryInterface;
use LandingPage\Form\Api\Data\FormDataInterfaceFactory;
use Magento\Customer\Api\CustomerRepositoryInterface;

class Post extends Action
{
    protected $formDataRepository;
    protected $formDataFactory;
    protected $logger;
    protected $customerRepository;

    public function __construct(
        Context $context,
        FormDataRepositoryInterface $formDataRepository,
        FormDataInterfaceFactory $formDataFactory,
        CustomerRepositoryInterface $customerRepository,
        LoggerInterface $logger
    ) {
        parent::__construct($context);
        $this->formDataRepository = $formDataRepository;
        $this->formDataFactory = $formDataFactory;
        $this->customerRepository = $customerRepository;
        $this->logger = $logger;
    }

    public function execute()
    {
        $postData = $this->getRequest()->getPostValue();

        if (!empty($postData)) {
            try {
                $customer = $this->getCustomer($postData['email']);
                if ($customer) {
                    $customerId = $customer->getId();
                   
                    $formData = $this->formDataFactory->create();
                    $formData->setCustomerId($customerId);
                    $formData->setComment($postData['comment']);
                   
                    $this->formDataRepository->save($formData);

                    $this->messageManager->addSuccessMessage(__('Form data saved successfully.'));
                } else {
                    $this->messageManager->addErrorMessage(__('Customer not found.'));
                }
            } catch (\Exception $e) {
                $this->logger->error($e->getMessage());
                $this->messageManager->addErrorMessage(__('An error occurred while saving the form data.'));
            }
        }

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }

    protected function getCustomer($email)
    {
        try {

            return $this->customerRepository->get($email);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            return null;
        }
    }
}