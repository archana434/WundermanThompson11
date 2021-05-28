<?php
namespace WundermanThompson\Import\Model;

use Magento\Framework\App\Helper\AbstractHelper;

class ImportCustomer extends AbstractHelper
{
    protected $website;
    protected $storeManager;
    protected $customerRepository;
    protected $state;
    protected $websiteId;

    public function __construct(
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository, 
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\State $state,
        \Magento\Framework\Filesystem\DirectoryList $directoryList,
        \Magento\Customer\Model\CustomerFactory $customerFactory
    )
    {
        $this->customerRepository = $customerRepository;
        $this->storeManager = $storeManager;
        $this->state = $state;
        $this->customerFactory = $customerFactory;
        $this->directoryList = $directoryList;
        $this->websiteId = $this->storeManager->getDefaultStoreView()->getWebsiteId();
    }

    public function run($name, $path)
    {
        if ($name == 'sample-json'){
            $this->importCustomerJson($path);
        }
        elseif($name == 'sample-csv'){
            $this->importCustomerCsv($path);
        }else{
            /* We can implement custom logger */
        }
    }

    public function importCustomerJson($path)
    {
        /*set path to the CSV file*/
        $file = fopen('var/import/'.$path, 'r');
        $jsonFilePath = $this->directoryList->getPath('var') . "/import/sample.json";

        if ($jsonFilePath !== false)
        {
            $fileContent = file_get_contents($jsonFilePath);
            $jsonfileContent = json_decode($fileContent, true);
            foreach ($jsonfileContent as $data)
            {
                $email = $data['emailaddress'];

                $websiteId = $this->websiteId;
                $customerObj = $this
                    ->customerFactory
                    ->create()
                    ->setWebsiteId($websiteId);
                $customer = $customerObj->loadByEmail($data['emailaddress']);
                try
                {
                    $customer->setFirstname($data['fname'])->setWebsiteId($websiteId)->setLastname($data['lname'])->setEmail($data['emailaddress']);

                    $customAttribute = $customer->getDataModel();
                    $customer->updateData($customAttribute);
                    $customer->save();
                }
                catch(\Exception $e)
                {
                    throw new \Magento\Framework\Exception\LocalizedException(__($e->getMessage()));
                }
            }
        }
    }

    public function importCustomerCsv($path)
    {
        /*set path to the JSON file*/
        $file = fopen('var/import/'.$path, 'r');
        if ($file !== false)
        {
            $header = fgetcsv($file);
            while ($row = fgetcsv($file, 3000, ","))
            {
                $data_count = count($row);
                if ($data_count < 1)
                {
                    continue;
                }

                $customerArr = [];
                $customerArr = array_combine($header, $row);
                $email = $customerArr['emailaddress'];

                $websiteId = $this->websiteId;
                $customerObj = $this
                    ->customerFactory
                    ->create()
                    ->setWebsiteId($websiteId);
                $customer = $customerObj->loadByEmail($customerArr['emailaddress']);
                try
                {
                    $customer->setFirstname($customerArr['fname'])
                        ->setWebsiteId($websiteId)
                        ->setLastname($customerArr['lname'])
                        ->setEmail($customerArr['emailaddress']);

                    $customAttribute = $customer->getDataModel();
                    $customer->updateData($customAttribute);
                    $customer->save();
                }
                catch(\Exception $e)
                {
                    throw new \Magento\Framework\Exception\LocalizedException(__($e->getMessage()));
                }
            }
        }
    }
}
