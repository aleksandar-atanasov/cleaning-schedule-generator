<?php declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use App\Services\GenerateScheduleData;
use League\Csv\CannotInsertRecord;
use App\Services\ExportScheduleToCsv;
use Carbon\CarbonPeriod;
use League\Csv\Writer;
use Carbon\Carbon;

class GenerateScheduleCommand extends Command
{

    /**
    * The name of the command.
    *
    * @var string
    */
    public const NAME = 'generate:schedule';

    /**
     * @author Aleksandar Atanasov
     * @param ExportScheduleToCsv $csvService
     * @param GenerateScheduleData $dataService
     */
    public function __construct(private ExportScheduleToCsv $csvService, private GenerateScheduleData $dataService)
    {
        parent::__construct();
    }

    /**
    * @author Aleksandar Atanasov
    * @return void
    */
    protected function configure() : void
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Generates the cleaning schedule of an office for the next three months by default when period not provided')
            ->addOption('period',null,InputOption::VALUE_OPTIONAL,'Override the default period of 3 months', 3);
    }

    /**
    * @author Aleksandar Atanasov
    * @param InputInterface $input
    * @param OutputInterface $output
    * @return int
    */
    public function execute(InputInterface $input, OutputInterface $output) : int
    {

        $periodMonths = (int)$input->getOption('period');

        $output->writeln("<info>Generating cleaning schedule for the next {$periodMonths} months...</>");

        try {
            $records = $this->dataService->handle($periodMonths);
        } catch (\Exception $ex) {
            $output->writeln("<error>{$ex->getMessage()}</>");
            exit(1);
        }
       
        $this->renderTable($records,$output);

        try {
           $fileLocation = $this->csvService->handle($records,$this->dataService->getHeaders());
        } catch (CannotInsertRecord $e) {
            $output->writeln("<error>Cannot insert record {$e->getRecord()}</>");
            exit(1);
        }

        $output->writeln('<info>Done!</>');
        $output->writeln("<info>CSV file from the table above was generated and can be found at $fileLocation</>");
        return Command::SUCCESS;
    }

    /**
     * @author Aleksandar Atanasov
     * @param array $records
     * @param OutputInterface $output
     * @return void
     */
    private function renderTable(array $records , OutputInterface $output) : void
    {
        $section = $output->section();

        $table = new Table($section);

        $table->setHeaders($this->dataService->getHeaders());

        foreach($records as $record){

            $table->addRow([
                $record['date'],
                $record['activity'],
                $record['duration']->format('H:i')
            ]);

        }
        $table->render();
    }
}