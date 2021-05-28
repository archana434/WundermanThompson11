<?php
namespace WundermanThompson\Import\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Framework\App\State as AppState;

class Import extends Command
{
    const NAME_ARGUMENT = "name";
    const NAME_FILEPATH = "path";

    protected $import;
    protected $appState;

    public function __construct(
        \WundermanThompson\Import\Helper\CustomerImport $import,
        AppState $appState
    ) {
        $this->import = $import;
        $this->appState = $appState;
        parent::__construct();
    }

    protected function configure()
    { 
        $this->setName("customer:import");
        $this->setDescription("Customer Import CSV/JSON");
        $this->setDefinition([
            new InputArgument(self::NAME_ARGUMENT, InputArgument::OPTIONAL, "FileName"),
            new InputArgument(self::NAME_FILEPATH, InputArgument::OPTIONAL, "Filepath")
        ]);
        parent::configure();
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ) {
        $this->appState->setAreaCode('adminhtml');
        $name = $input->getArgument(self::NAME_ARGUMENT);
        $path = $input->getArgument(self::NAME_FILEPATH);
        $this->import->execute($name, $path);
        $output->writeln("Customer Job Completed. Please verify log.");
    }
}
