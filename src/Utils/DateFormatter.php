<?php

namespace App\Utils;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DateInterval;
use DatePeriod;
use DateTime;

class DateFormatter
{
    /**
     * Format a date now
     * @return string
     */
    public static function now(): string
    {
        return Carbon::now()->isoFormat('D MMMM YYYY');
    }

    /**
     * Convert Indonesian month names to English.
     *
     * @param string $dateString
     * @return string
     */
    public static function convertMonthToEng($dateString): string
    {
        $monthMapping = [
            'Januari'   => 'January',
            'Februari'  => 'February',
            'Maret'     => 'March',
            'April'     => 'April',
            'Mei'       => 'May',
            'Juni'      => 'June',
            'Juli'      => 'July',
            'Agustus'   => 'August',
            'September' => 'September',
            'Oktober'   => 'October',
            'November'  => 'November',
            'Desember'  => 'December',
        ];

        foreach ($monthMapping as $monthIndo => $monthEng) {
            $dateString = str_replace($monthIndo, $monthEng, $dateString);
        }
        return $dateString;
    }

    /**
     * Format a date string from Indonesian to 'Y-m-d'.
     *
     * @param string|null $dateString
     * @param string $defaultYear
     * @return string
     */
    public static function formatDateString($dateString = null, $defaultYear = '2000'): string
    {
        try {
            if ($dateString) {
                $dateString = self::convertMonthToEng($dateString);
                return Carbon::createFromFormat('j F Y', $dateString)->format('Y-m-d');
            } else {
                return Carbon::createFromFormat('Y', $defaultYear)->startOfYear()->format('Y-m-d');
            }
        } catch (\Exception $e) {
            return Carbon::createFromFormat('Y', $defaultYear)->startOfYear()->format('Y-m-d');
        }
    }

    /**
     * Format a date using ISO
     * @param $date
     * @param  null  $format
     * @return string
     */
    public static function dateAt($date, $format = null): string
    {
        return Carbon::parse($date)->isoFormat($format ?? 'DD MMMM YYYY');
    }

    /**
     * Format a time using ISO
     * @param $time
     * @param  null  $format
     * @return string
     */
    public static function timeAt($time, $format = null): string
    {
        return Carbon::parse($time)->isoFormat($format ?? 'H:mm:s'). ' WITA';
    }

    /**
     * Format a datetime using ISO
     * @param $date
     * @param  null  $format
     * @return string
     */
    public static function dateTimeAt($date, $format = null): string
    {
        return Carbon::parse($date)->isoFormat($format ?? 'DD MMMM YYYY H:mm:s'). ' WITA';
    }

    /**
     * Get the names of the days of the week.
     *
     * @return array
     */
    public static function getDaysOfWeek(): array
    {
        return ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
    }

    /**
     * Get the names of the months of the year.
     *
     * @return array
     */
    public static function getMonthsOfYear(): array
    {
        return [
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember',
        ];
    }

    /**
     * Get the start of the week, end of the week, and all dates within the week for the given date.
     *
     * @param  string|null  $dateFilter
     * @return array
     */
    public static function getWeekDates(string $dateFilter = null): array
    {
        $date = $dateFilter ?? Carbon::now();
        $startOfWeek = Carbon::parse($date)->startOfWeek();
        $endOfWeek = Carbon::parse($date)->endOfWeek();
        $datesInWeek = CarbonPeriod::create($startOfWeek, $endOfWeek)->toArray();

        return [
            'start_of_week' => $startOfWeek,
            'end_of_week' => $endOfWeek,
            'dates_in_week' => $datesInWeek,
        ];
    }

    /**
     * Get the start of the month, end of the month, and all dates within the month for the given date.
     *
     * @param  string|null  $dateFilter
     * @return array
     */
    public static function getMonthDates(string $dateFilter = null): array
    {
        $date = $dateFilter ?? Carbon::now();
        $startOfMonth = Carbon::parse($date)->startOfMonth();
        $endOfMonth = Carbon::parse($date)->endOfMonth();
        $datesInMonth = CarbonPeriod::create($startOfMonth, $endOfMonth)->toArray();

        return [
            'start_of_month' => $startOfMonth,
            'end_of_month' => $endOfMonth,
            'dates_in_month' => $datesInMonth,
        ];
    }

    /**
     * @param  int  $year
     * @param  int  $month
     * @return array
     */
    public static function getDatesInMonth(int $year, int $month): array
    {
        Carbon::setLocale('id');

        $firstDate = Carbon::create($year, $month);
        $endDate   = Carbon::create($year, $month)->addMonths()->addDays(-1);
        $dates     = CarbonPeriod::create($firstDate, $endDate);

        return [
            'startInMonth' => $firstDate,
            'endInMonth' => $endDate,
            'datesInMonth' => $dates,
        ];
    }

    /**
     * Get the start of the year, end of the year, and all dates within the specified month and year.
     *
     * @param  string|null  $dateFilter
     * @param  int|null  $year
     * @param  int|null  $month
     * @return array
     */
    public static function getDatesInYear(string $dateFilter = null, int $year = null, int $month = null): array
    {
        if ($dateFilter) {
            $date = Carbon::parse($dateFilter);
            $year = $date->year;
            $month = $date->month;
        } else {
            $year = $year ?? Carbon::now()->year;
            $month = $month ?? null;
        }

        if ($month) {
            $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth();
            $endOfMonth = Carbon::create($year, $month, 1)->endOfMonth();
            $datesInMonth = CarbonPeriod::create($startOfMonth, $endOfMonth)->toArray();

            return [
                'start_of_month' => $startOfMonth,
                'end_of_month' => $endOfMonth,
                'dates_in_month' => $datesInMonth,
            ];
        } else {
            $startOfYear = Carbon::create($year, 1, 1)->startOfDay();
            $endOfYear = Carbon::create($year, 12, 31)->endOfDay();
            $datesInYear = CarbonPeriod::create($startOfYear, $endOfYear)->toArray();

            return [
                'start_of_year' => $startOfYear,
                'end_of_year' => $endOfYear,
                'dates_in_year' => $datesInYear,
            ];
        }
    }

    /**
     * Get lifetime based on date
     *
     * @param  string  $date
     * @param  int  $days
     * @param  null  $format
     * @return string
     */
    public static function lifetime(string $date, int $days, $format = null): string
    {
        return Carbon::parse($date)->addDays($days)->format($format ?? 'Y-m-d H:i:s');
    }

    /**
     * Get dates from date range
     * @param $start
     * @param $end
     * @param $format
     * @return array
     * @throws \Exception
     */
    public static function getDatesFromRange($start, $end, $format = 'Y-m-d'): array
    {
        $array = array();
        $interval = new DateInterval('P1D');

        $realEnd = new DateTime($end);
        $realEnd->add($interval);

        $period = new DatePeriod(new DateTime($start), $interval, $realEnd);

        foreach($period as $date) {
            $array[] = $date->format($format);
        }

        return $array;
    }

    /**
     * Get start date from date range
     * @param $value
     * @return Carbon
     */
    public static function getStartFromRange($value): Carbon
    {
        $dateRange = explode(" - ", $value) ?? null;
        return Carbon::parse($dateRange[0]);
    }

    /**
     * Get end date from date range
     * @param $value
     * @return Carbon
     */
    public static function getEndFromRange($value): Carbon
    {
        $dateRange = explode(" - ", $value) ?? null;
        return Carbon::parse($dateRange[1]);
    }

    /**
     * Get month list
     * @param $start
     * @param $end
     * @return array
     */
    public static function getMonthList($start, $end): array
    {
        $months = [];
        for ($i = $start ; $i <= $end; $i++) {
            $months[$i] = Carbon::createFromTimestamp(mktime(0,0,0,$i,1,date('Y')))->isoFormat('MMMM');
        }
        return $months;
    }

    /**
     * Get year list
     * @param $start
     * @param $end
     * @return array
     */
    public static function getYearList($start, $end): array
    {
        $years = [];
        for ($i = $start ; $i <= $end; $i++) {
            $years[$i] = $i;
        }
        return $years;
    }

    /**
     * Merge date & time
     */
    public static function dateTime($date, $time)
    {
        return Carbon::parse($date)->isoFormat('DD MMMM Y') .'<br>'.
               Carbon::parse($time)->isoFormat(' HH:mm:ss') . ' WITA';
    }
}
