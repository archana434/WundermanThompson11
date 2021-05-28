# WundermanThompson_Import

WundermanThompson_Import module used to import customer with basic data.

## Installation

In magento root directory, execute:
```bash
php bin/magento module:enable WundermanThompson_Import
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento c:f
```

## Usage

Cli commands are,
bin/magento customer:import sample-csv sample.csv
bin/magento customer:import sample-json sample.json


Sample files needs to be placed with in var/import/ directory.
