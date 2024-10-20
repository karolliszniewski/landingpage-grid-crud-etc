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
