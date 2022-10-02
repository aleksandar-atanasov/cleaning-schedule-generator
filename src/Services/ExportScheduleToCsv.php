<?php declare(strict_types=1);

namespace App\Services;

use League\Csv\Writer;

class ExportScheduleToCsv
{
    /**
     * @author Aleksandat Atanasov
     * @param iterable $records
     * @param array $headers
     * @return string
     */
    public function handle(iterable $records, array $headers) : string
    {
        $filePath = './exports' . $this->makeFileName();
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
}