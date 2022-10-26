<?php
/**
 * Created by PhpStorm.
 * User: apremiera
 * Date: 07.08.17
 * Time: 20:08
 */

namespace GanttChartBundle\Model;

use DateTime;

/**
 * Class JiraItem
 * @package GanttChartBundle\Model
 * Модель задачи Jira
 */
class JiraItem
{
    const IMPORTANT = 'important';
    const WARNING = 'warning';
    const PROGRESS = 'progress';
    const NORMAL = '';

    /**
     * @var jira id
     */
    private $id;

    /**
     * @var string jira key
     */
    private $key;

    /**
     * @var string Название задачи
     */
    private $name;

    /**
     * @var string Исполнитель
     */
    private $assignee;

    /**
     * @var DateTime Дата начала работы
     */
    private $startDate;

    /**
     * @var DateTime Дата окончания
     */
    private $dueDate;

    /**
     * @var DateTime
     */
    private $createDate;

    /**
     * @var integer Оценка времени в секундах
     */
    private $timeEstimate;

    /**
     * @var integer Затраченное время в секундах
     */
    private $timeSpent;

    /**
     * @var string Статусы (исполняется, решена, прочее)
     */
    private $status;

    /**
     * @var string Тип задачи (оперативная, разработка кода)
     */
    private $issueType;

    /**
     * @string Свойства для отрисовки
     */
    private $viewStatus;

    /**
     * @var \stdClass
     */
    private $overParams;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param mixed $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getAssignee()
    {
        return $this->assignee;
    }

    /**
     * @param mixed $assignee
     */
    public function setAssignee($assignee)
    {
        $this->assignee = $assignee;
    }

    /**
     * @return DateTime
     */
    public function getStartDate() : DateTime
    {
        return $this->startDate;
    }

    /**
     * @param $startDate DateTime
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
    }

    /**
     * @return mixed
     */
    public function getDueDate()
    {
        return $this->dueDate;
    }

    /**
     * @param $dueDate mixed
     */
    public function setDueDate($dueDate)
    {
        $this->dueDate = $dueDate;
    }

    /**
     * @return DateTime
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }

    /**
     * @param DateTime $createDate
     */
    public function setCreateDate($createDate)
    {
        $this->createDate = $createDate;
    }

    /**
     * @return integer
     */
    public function getTimeEstimate()
    {
        return $this->timeEstimate;
    }

    /**
     * @param mixed $timeEstimate
     */
    public function setTimeEstimate($timeEstimate)
    {
        $this->timeEstimate = $timeEstimate;
    }

    /**
     * @return int
     */
    public function getTimeSpent()
    {
        return $this->timeSpent;
    }

    /**
     * @param int $timeSpent
     */
    public function setTimeSpent($timeSpent)
    {
        $this->timeSpent = $timeSpent;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getIssueType()
    {
        return $this->issueType;
    }

    /**
     * @param mixed $issueType
     */
    public function setIssueType($issueType)
    {
        $this->issueType = $issueType;
    }

    /**
     * @return mixed
     */
    public function getViewStatus()
    {
        return $this->viewStatus;
    }

    /**
     * @param $viewStatus
     */
    public function setViewStatus($viewStatus)
    {
        $this->viewStatus = $viewStatus;
    }

    /**
     * @return mixed
     */
    public function getOverParams()
    {
        return $this->overParams;
    }

    /**
     * @param mixed $overParams
     */
    public function setOverParams($overParams)
    {
        $this->overParams = $overParams;
    }



}