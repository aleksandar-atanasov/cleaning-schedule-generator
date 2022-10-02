<?php declare(strict_types=1);

namespace App\Services;

use League\Csv\Writer;

class ExportScheduleToCsv
{
    /**
     * @var string
     */
    private static $exportsDir = './exports';

    /**
     * @author Aleksandat Atanasov
     * @param iterable $records
     * @param array $headers
     * @return string
     */
    public function handle(iterable $records, array $headers) : string
    {
      
        $directory = $this->assertExportsDirExists();

        $filePath = $directory . $this->makeFileName();

        $writer = Writer::createFromPath($filePath, 'w+');

        $writer->insertOne($headers);

        foreach($records as $record){
            $writer->insertOne([
                $record['date'],
                $record['activity'],
                $record['duration']->format('H:i'),
            ]); 
        }

        return $filePath;
    }

    /**
     * @author Aleksandat Atanasov
     * @return string
     */
    private function makeFileName() : string
    {
        return '/cleaning_schedule_' . md5(time().uniqid()) . '.csv';
    }

    /**
     * @author Aleksandar Atanasov
     * @return string
     */
    private function assertExportsDirExists() : string
    {
        if(!is_dir(self::$exportsDir)){
            mkdir(self::$exportsDir);
        }

        return self::$exportsDir;
    }
}