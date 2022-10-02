<?php declare(strict_types=1);

namespace App\Services;

use Carbon\Carbon;
use Carbon\CarbonPeriod;


class GenerateScheduleData 
{

    private static $headers = ['Date', 'Activities', 'Total_Time'];

    /**
    * @author Aleksandar Atanasov
    * @param int|null $periodMonths defaults to 3 months 
    * @return array
    */
    public function handle(?int $periodMonths = 3) : array
    {
        $start = Carbon::now();

        $end = $start->clone()->addMonths($periodMonths);

        $dates = $this->getDatesFromRange($start->format('Y-m-d'),$end->format('Y-m-d'));

        $data = [];

        foreach($dates as $date){

            if($date->isTuesday() || $date->isThursday()){

                $data[$date->format('d-m-Y')] = [
                    'date'     => $date->format('d-m-Y'),
                    'activity' => 'Vacuuming',
                    'duration' => Carbon::createFromFormat('d-m-Y H:i',$date->format('d-m-Y') . '00:21' )
                ];
            }

            $lastFriday = new Carbon('last Friday of ' . $date->format('F Y'));
            // assuming that the last working day of the month is Friday
            if($date->isFriday() && $date->isSameDay($lastFriday)){

                $data[$date->format('d-m-Y')] = [
                    'date'     => $date->format('d-m-Y'),
                    'activity' => 'Window cleaning',
                    'duration' => Carbon::createFromFormat('d-m-Y H:i',$date->format('d-m-Y') . '00:35' )
                ];
            }

            $firstVacumingDayOfTheMonth = new Carbon('first Tuesday of ' . $date->format('F Y'));

            if($date->isTuesday() && $date->isSameDay($firstVacumingDayOfTheMonth)){

                $data[$date->format('d-m-Y')]['activity'] .= ', Refrigerator cleaning';
                $data[$date->format('d-m-Y')]['duration']->addMinutes(50);
            }
        }

        return $data;
    }

    /**
     * @author Aleksandar Atanasov
     * @return array
     */
    public function getHeaders() : array
    {
        return self::$headers;
    }

    /**
     * Returns dates array of Carbon objects from given date range
     * 
     * @author Aleksandar Atanasov
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    private function getDatesFromRange(string $startDate, string $endDate) : array
    {
        $period = CarbonPeriod::create($startDate, $endDate);
        return $period->toArray();
    }
}