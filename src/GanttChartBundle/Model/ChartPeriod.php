<?php
/**
 * Created by PhpStorm.
 * User: apremiera
 * Date: 16.08.17
 * Time: 19:45
 */

namespace GanttChartBundle\Model;

/**
 * Class ChartPeriod
 * Класс строит и декорирует календарь периода дат для диаграммы
 * Внимание! Смещение дат для отрисовки от заданного периода на -+ PERIOD_OFFSET
 * @package GanttChartBundle\Model
 */
class ChartPeriod extends \DateTime
{

    /**
     * @TODO Разделить период для декорирования и логику обработки начальной и конечной даты !?
     * Смещение начальной и конечной даты
     */
    const PERIOD_OFFSET = 1;

    /**
     * Ширина и высота отображения ячейки
     */
    const CELL_WIDTH = 35;
    const CELL_HEIGHT = 35;

    /**
     * @var integer
     */
    public $periodCountDay;

    /**
     * @var \DateTime
     */
    private $startPeriodDate;

    /**
     * @var \DateTime
     */
    private $endPeriodDate;

    /**
     * ChartPeriod constructor.
     * @param \DateTime $startPeriodDate
     * @param \DateTime $endPeriodDate
     */
    public function __construct(\DateTime $startPeriodDate, \DateTime $endPeriodDate)
    {
        $this->setStartPeriodDate($startPeriodDate);
        $this->setEndPeriodDate($endPeriodDate);
    }

    /**
     * @param $item
     * @return int
     */
    public function getItemBlockWidth($item)
    {
        $interval = new \DateInterval('P1D');
        $period = new \DatePeriod($item->getStartDate(), $interval, $item->getDueDate());

        $itemBlockWidth = iterator_count($period);

        if($itemBlockWidth === 0) {
            $itemBlockWidth = 1;
        }
        return $itemBlockWidth * self::CELL_WIDTH;
    }

    /**
     * @param \DateTime $itemDate
     * @return integer
     * Вычисляет сдвиг слева для отображения сущности
     */
    public function getItemLeftOffset(\DateTime $itemDate = NULL): int
    {
        if($itemDate === NULL) {
            $itemDate = new \DateTime();
        }
        $itemDate->modify('00:00:00');

        $diff = $itemDate->getTimestamp() - $this->getStartPeriodDate()->getTimestamp();
        $offset = round($diff / (60*60*24));

        $offset = $offset * self::CELL_WIDTH;
        return $offset;
    }
    /**
     * @param string $format
     * @return string
     */
    public function getToday( $format = 'Y-m-d' ): string
    {
        return (new \DateTime())->format($format);
    }

    /**
     * @param \DateTime $item
     * @return bool
     */
    public function isTodayDay(\DateTime $item): bool
    {
        $format = 'Y-m-d';
        return $this->getToday($format) === $item->format($format);
    }

    /**
     * @param \DateTime $item
     * @return bool
     */
    public function isWeekendDay(\DateTime $item): bool
    {
        if($item->format('N') == 6 || $item->format('N') == 7){
            return true;
        }

        return false;
    }

    /**
     * Возвращает количество дней для каждого месяца из периода, включая начальную дату
     * @param \DateTime $date Объект
     * @return int
     */
    public function getDaysInPeriodMonth(\DateTime $date): int
    {
        $interval = new \DateInterval('P1D');
        $days = $date->format('t');

        if($date->format('Y-m') === $this->startPeriodDate->format('Y-m')) {
            $compareDate = (new \DateTime($date->format('Y-m-d')))
                ->modify('last day of this month')
                ->modify('23:00:00');

            $period = new \DatePeriod($this->startPeriodDate, $interval, $compareDate);
            $days = iterator_count($period);
        }

        if($date->format('Y-m') === $this->endPeriodDate->format('Y-m')) {
            $compareDate = (new \DateTime($date->format('Y-m-d')))
                ->modify('first day of this month')
                ->modify('00:00:00');

            $period = new \DatePeriod($compareDate, $interval, $this->endPeriodDate);
            $days = iterator_count($period);
        }

        return $days;
    }

    /**
     * @return int
     */
    public function getPeriodDayCount(): int
    {
        $period = $this->getPeriodByDay();

        return iterator_count($period);
    }

    /**
     * @return int
     */
    public function getPeriodMonthCount(): int
    {
        $period = $this->getPeriodByMonth();

        return iterator_count($period);
    }

    /**
     * Возвращает период
     * @return \DatePeriod
     */
    public function getPeriodByDay(): \DatePeriod
    {
        $interval = new \DateInterval('P1D');
        $period = new \DatePeriod($this->startPeriodDate, $interval, $this->endPeriodDate);

        return $period;
    }

    public function getPeriodByMonth(): \DatePeriod
    {
        $interval = new \DateInterval('P1M');
        $startDate = (new \DateTime($this->startPeriodDate->format('Y-m-d')))
            ->modify('first day of this month');

        $endDate = (new \DateTime($this->endPeriodDate->format('Y-m-d')))
            ->modify('first day of next month');

        $period = new \DatePeriod($startDate, $interval, $endDate);

        return $period;
    }

    /**
     * @return \DateTime
     */
    public function getStartPeriodDate(): \DateTime
    {
        return $this->startPeriodDate;
    }

    /**
     * @param \DateTime $startPeriodDate
     */
    public function setStartPeriodDate(\DateTime $startPeriodDate)
    {
        $this->startPeriodDate = new \DateTime($startPeriodDate->format('Y-m-d'));
        $this->startPeriodDate->modify('-'. self::PERIOD_OFFSET .' day');
        $this->startPeriodDate->modify('00:00:00');
    }

    /**
     * @return \DateTime
     */
    public function getEndPeriodDate(): \DateTime
    {
        return $this->endPeriodDate;
    }

    /**
     * @param \DateTime $endPeriodDate
     */
    public function setEndPeriodDate(\DateTime $endPeriodDate)
    {
        $this->endPeriodDate = new \DateTime($endPeriodDate->format('Y-m-d'));
        $this->endPeriodDate->modify('23:59:59');
        $this->endPeriodDate->modify('+'. self::PERIOD_OFFSET .' day');
    }

    /**
     * @return int
     */
    public function getCellWidth()
    {
        return self::CELL_WIDTH;
    }

    /**
     * @return int
     */
    public function getCellHeight()
    {
        return self::CELL_HEIGHT;
    }


}