<?php
/**
 * Created by PhpStorm.
 * User: apremiera
 * Date: 22.08.17
 * Time: 18:28
 */

namespace tests\GanttChartBundle\Model;

use GanttChartBundle\Model\ChartPeriod;

class ChartPeriodTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return array
     */
    public function datePeriodProvider()
    {
        $start = (new \DateTime('2017-5-10 00:00:00'));
        $end = (new \DateTime('2017-7-11 23:59:59'));

        return [
            'Check count days in each month of period INCLUDE first period date' => [
                $start,
                $end,
                [
                    22,
                    29, // 30 дней - ChartPeriod::PERIOD_OFFSET
                    11
                ]
            ]
        ];
    }

    /**
     * Тест подогнан под реализацию.
     * @dataProvider datePeriodProvider
     */
    public function testGetDaysInPeriodMonth($start, $end, $expected)
    {
        $chartPeriod = new ChartPeriod($start, $end);
        foreach ($chartPeriod->getPeriodByMonth() as $key => $item) {
            $result = $chartPeriod->getDaysInPeriodMonth($item);
            $expectValue = $expected[$key] + $chartPeriod::PERIOD_OFFSET;
            $this->assertEquals($result, $expectValue);
        }
    }
}
