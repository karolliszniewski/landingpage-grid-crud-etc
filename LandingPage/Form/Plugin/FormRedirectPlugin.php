<?php

namespace LandingPage\Form\Plugin;

use Magento\Framework\App\Response\RedirectInterface;
use Magento\Customer\Model\Session;
use Psr\Log\LoggerInterface;
use LandingPage\Form\Api\FormDataRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\UrlInterface;

class FormRedirectPlugin {
    protected $redirect;
    protected $customerSession;
    protected $logger;
    protected $formDataRepository;
    protected $searchCriteriaBuilder;
    protected $url;
    protected $response;
    

    public function __construct(
        RedirectInterface $redirect,
        Session $customerSession,
        LoggerInterface $logger,
        FormDataRepositoryInterface $formDataRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        UrlInterface $url,
        ResponseInterface $response

    ) {
        $this->redirect = $redirect;
        $this->customerSession = $customerSession;
        $this->logger = $logger;
        $this->formDataRepository = $formDataRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->url = $url;
        $this->response = $response;
    }

    public function beforeExecute($subject, ...$args) {
        $customerId = $this->customerSession->getCustomerId();

      
        if ($customerId) {
            $formData = $this->getFormDataByCustomerId($customerId);

            $redirectUrl = $this->url->getUrl('https://www.audio-technica.com/en-gb/at-sb727');
        //   $this->response->setRedirect($redirectUrl);

            return;
            

        } else {
            $this->logger->error(__('No customer ID found in session.'));
        }
    }

    protected function getFormDataByCustomerId($customerId) {
        try {
            // Set up search criteria
            $this->searchCriteriaBuilder->addFilter('customer_id', $customerId);
            $searchCriteria = $this->searchCriteriaBuilder->create();

            // Ensure searchCriteria is not null before calling getList
            if ($searchCriteria) {

                $result = $this->formDataRepository->getList($searchCriteria); 
             
                return $result->getTotalCount() >= 1;
            } else {
                $this->logger->error(__('Failed to create SearchCriteria.'));
                return null;
            }
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            return null;
        }
    }
}