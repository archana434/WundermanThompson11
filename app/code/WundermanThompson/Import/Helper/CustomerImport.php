<?php
namespace WundermanThompson\Import\Helper;

class CustomerImport
{
    protected $logger;
    protected $import;

    /**
     * CustomerImport constructor.
     * @param \Psr\Log\LoggerInterface $logger
     * @param \WundermanThompson\Import\Model\ImportCustomer $importCustomer
     */
    public function __construct(\Psr\Log\LoggerInterface $logger, \WundermanThompson\Import\Model\ImportCustomer $importCustomer)
    {
        $this->logger = $logger;
        $this->importCustomer = $importCustomer;
    }

    /**
     * Execute the cron
     *
     * @return void
     */
    public function execute($name, $path)
    {
        $this->logger->addInfo("Cronjob Import Started.");
        if($name == 'sample-csv'){
            $this
                ->logger
                ->addInfo("Customer CSV Import Started.");
            $this
                ->importCustomer
                ->run($name, $path);
            $this
                ->logger
                ->addInfo("Customer CSV Import finished.");
        }elseif($name == 'sample-json'){
            $this
                ->logger
                ->addInfo("Customer JSON Import Started.");
            $this
                ->importCustomer
                ->run($name, $path);
            $this
                ->logger
                ->addInfo("Customer JSON finished.");
        }else{
            $this->logger->addInfo("Please mention proper import type");
        }
    }
}
