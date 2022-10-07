<?php declare(strict_types=1);

namespace App\Services;

use App\Models\RefrigeratorCleaning;
use App\Models\WindowCleaning;
use InvalidArgumentException;
use App\Helpers\DateHelper;
use App\Models\Vacuuming;
use Carbon\CarbonPeriod;
use Carbon\Carbon;

class GenerateScheduleData 
{

    /**
    * @var array
    */
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

        $dates = DateHelper::getDatesFromRange($start->format('Y-m-d'),$end->format('Y-m-d'));

        $data = [];

        foreach($dates as $date){

            if(DateHelper::isVacuumingDay($date)){
                $vacuuming = Vacuuming::make();
                $data[$date->format('d-m-Y')] = [
                    'date'     => $date->format('d-m-Y'),
                    'name'     => $vacuuming->getName(),
                    'duration' => $vacuuming->getDuration()
                ];
            }

            $lastDayOfTheMonth = DateHelper::getLastDayOfTheMonthFromDate($date);
          
            $windowCleaning = WindowCleaning::make();
            // Not taking into account the bank holidays/national holidays
            if(DateHelper::isTheLastDayOfTheMonthWeekday($date) && $date->isSameDay($lastDayOfTheMonth)){

                $lastWorkingDayOfTheMonth = $date;

                if(DateHelper::isVacuumingDay($lastWorkingDayOfTheMonth)){

                    $data[$lastWorkingDayOfTheMonth->format('d-m-Y')]['name'] .= ', ' . $windowCleaning->getName();

                    $data[$lastWorkingDayOfTheMonth->format('d-m-Y')]['duration'] += $windowCleaning->getDuration();

                }else{
                    $data[$lastWorkingDayOfTheMonth->format('d-m-Y')] = [
                        'date'     => $date->format('d-m-Y'),
                        'name'     => $windowCleaning->getName(),
                        'duration' => $windowCleaning->getDuration()
                    ];
                }
            }

            //if the last day of the month is during the weekend get the last working day e.g(friday)
            if(DateHelper::isTheLastDayOfTheMonthDuringTheWeekend($date) && $date->isSameDay($lastDayOfTheMonth)){

                $lastWorkingDayOfTheMonth = $lastDayOfTheMonth->isSaturday() 
                                            ? $date->clone()->subDay(1) 
                                            : ($lastDayOfTheMonth->isSunday() 
                                            ? $date->clone()->subDays(2) 
                                            : $date);

                $data[$lastWorkingDayOfTheMonth->format('d-m-Y')] = [
                    'date'     => $lastWorkingDayOfTheMonth->format('d-m-Y'),
                    'name'     => $windowCleaning->getName(),
                    'duration' => $windowCleaning->getDuration()
                ];
            }
            
            if(DateHelper::isTheFirstVacuumingDayOfTheMonth($date)){

                $refrigeratorCleaning = RefrigeratorCleaning::make();

                $data[$date->format('d-m-Y')]['name'] .= ', ' . $refrigeratorCleaning->getName();

                $data[$date->format('d-m-Y')]['duration'] += $refrigeratorCleaning->getDuration();
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
}