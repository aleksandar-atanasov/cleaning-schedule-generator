<?php declare(strict_types=1);

namespace App\Helpers;

use Carbon\CarbonPeriod;
use Carbon\Carbon;

class DateHelper
{
    /**
    * @author Aleksandar Atanasov aleksandara@internal>
    * @param int $seconds
    * @param string|null $format
    * @return string
    */
    public static function secondsToTime(int $seconds, ?string $format='%02d:%02d:%02d') : string
    {
        return sprintf(
            $format,
            floor($seconds / 3600),
            floor($seconds / 60 % 60),
            floor($seconds % 60)
        );
    }

    /**
    * Returns dates array of Carbon objects from given date range
    * 
    * @author Aleksandar Atanasov
    * @param string $startDate
    * @param string $endDate
    * @return array
    */
    public static function getDatesFromRange(string $startDate, string $endDate) : array
    {
        $period = CarbonPeriod::create($startDate, $endDate);
        return $period->toArray();
    }

    /**
    * @author Aleksandar Atanasov
    * @param Carbon $date
    * @return bool
    */
    public static function isVacuumingDay(Carbon $date) : bool
    {
        return ($date->isTuesday() || $date->isThursday());
    }

    /**
    * @author Aleksandar Atanasov
    * @param Carbon $date
    * @return bool
    */
    public static function isTheFirstVacuumingDayOfTheMonth(Carbon $date) : bool
    {
        $firstVacumingDayOfTheMonth = new Carbon('first Tuesday of ' . $date->format('F Y'));
        return $date->isTuesday() && $date->isSameDay($firstVacumingDayOfTheMonth);
    }

    /**
    * @author Aleksandar Atanasov
    * @param Carbon $date
    * @return Carbon
    */
    public static function getLastDayOfTheMonthFromDate(Carbon $date) : Carbon
    {
        return new Carbon('last day of ' . $date->format('F Y'));
    }

    /**
    * @author Aleksandar Atanasov
    * @param Carbon $date
    * @return bool
    */
    public static function isTheLastDayOfTheMonthWeekday(Carbon $date) : bool
    {
        return self::getLastDayOfTheMonthFromDate($date)->isWeekday();
    }

    /**
    * @author Aleksandar Atanasov
    * @param Carbon $date
    * @return bool
    */
    public static function isTheLastDayOfTheMonthDuringTheWeekend(Carbon $date) : bool
    {
        return !self::isTheLastDayOfTheMonthWeekday($date);
    }
}