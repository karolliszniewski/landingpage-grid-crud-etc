Last update 30.09.2024
```bash
└── Form
    ├── Api
    │   ├── Data
    │   │   └── FormDataInterface.php
    │   └── FormDataRepositoryInterface.php
    ├── Block
    │   └── Index.php
    ├── Controller
    │   ├── Adminhtml
    │   │   └── Grid
    │   │       └── Index.php
    │   └── Index
    │       ├── Index.php
    │       └── Post.php
    ├── Helper
    │   └── Data.php
    ├── Model
    │   ├── FormData.php
    │   ├── FormDataRepository.php
    │   └── ResourceModel
    │       ├── FormData
    │       │   └── Collection.php
    │       └── FormData.php
    ├── Plugin
    │   ├── AddAttributesToUiDataProvider.php
    │   └── FormRedirectPlugin.php
    ├── Ui
    │   └── DataProvider
    │       └── Category
    │           └── ListingDataProvider.php
    ├── etc
    │   ├── adminhtml
    │   │   ├── menu.xml
    │   │   ├── routes.xml
    │   │   └── system.xml
    │   ├── db_schema.xml
    │   ├── di.xml
    │   ├── frontend
    │   │   └── routes.xml
    │   └── module.xml
    ├── registration.php
    └── view
        ├── adminhtml
        │   ├── layout
        │   │   └── landingpage_grid_index.xml
        │   └── ui_component
        │       └── landingpage_grid_listing.xml
        └── frontend
            ├── layout
            │   └── landingpage_index_index.xml
            └── templates
                ├── content
                │   └── content.phtml
                ├── form
                │   └── form.phtml
                └── no-form
                    ├── disabled.phtml
                    └── session.phtml
```

Sae without entity manager. Not recomended but is working
```php
<?php

namespace LandingPage\Form\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Store\Model\StoreManagerInterface;

class FormData extends AbstractDb
{
    /**
     * @param Context $context
     * @param string|null $connectionName
     */
    public function __construct(
        Context $context,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
    }

    /**
     * Define main table and primary key
     */
    protected function _construct()
    {
        // Table name and primary key field
        $this->_init('landingpage_form', 'id');
    }

    /**
     * Save an object without using EntityManager.
     *
     * @param AbstractModel $object
     * @return $this
     * @throws \Exception
     */
    public function save(AbstractModel $object): self
    {
        $data = $object->getData();

        if ($object->isObjectNew()) {
            // Insert new record
            $this->getConnection()->insert($this->getMainTable(), $data);
        } else {
            // Update existing record
            $this->getConnection()->update($this->getMainTable(), $data, ['id = ?' => $object->getId()]);
        }

        return $this;
    }
}
```


to read session 
`app/code/LandingPage/Form/Block/Index.php`
```php
<?php

namespace LandingPage\Form\Block;

use Magento\Framework\View\Element\Template;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Customer\Model\Url as CustomerUrl;
use LandingPage\Form\Helper\Data as FormHelper;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\View\Element\Template\Context;
use Magento\Customer\Model\SessionFactory;

class Index extends Template
{
    const ENGLISH_STORE = 'en_gb';

    protected $customerSession;
    protected $customerUrl;
    protected $storeManager;
    protected $formHelper;
    protected $sessionFactory;

    /**
     * Construct
     *
     * @param Context $context
     * @param CustomerSession $customerSession
     * @param CustomerUrl $customerUrl
     * @param StoreManagerInterface $storeManager
     * @param FormHelper $formHelper
     * @param array $data
     */
    public function __construct(
        Context $context,
        CustomerSession $customerSession,
        CustomerUrl $customerUrl,
        StoreManagerInterface $storeManager,
        FormHelper $formHelper,
        SessionFactory $sessionFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->customerSession = $customerSession;
        $this->customerUrl = $customerUrl;
        $this->storeManager = $storeManager;
        $this->formHelper = $formHelper;
        $this->sessionFactory = $sessionFactory;
    }

    protected function getCustomer()
    {
        $customerSession = $this->sessionFactory->create();
        return $customerSession->getCustomer();
    }

    public function isCustomerLoggerIn()
    {

        $customerSession = $this->sessionFactory->create();
        return $customerSession->getCustomer()->getId() ? true : false;
    }

    public function getCustomerName()
    {
        $customer = $this->getCustomer();

        $name = $customer->getName();

        return $name;
    }

    public function getCustomerEmail()
    {
        $customer = $this->getCustomer();
        $email = $customer->getEmail();

        return $email;
    }

    public function getLoginUrl()
    {
        return $this->getUrl('customer/account/login');
    }

    /**
     * Check if the module is enabled
     *
     * @return bool
     */
    public function isModuleEnabled()
    {
        return $this->formHelper->isModuleEnabled();
    }

    public function isEnglishStore()
    {
        return $this->storeManager->getStore()->getCode() === self::ENGLISH_STORE;
    }
}


```



lets check this form update / edit customer address

`vendor/magento/module-customer/Block/Address/Edit.php`

`vendor/magento/module-customer/Controller/Address/FormPost.php` - here is save() lets see how it is working



