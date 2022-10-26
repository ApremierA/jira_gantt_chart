<?php
/**
 * Created by PhpStorm.
 * User: apremiera
 * Date: 07.08.17
 * Time: 20:00
 * Класс преобразует получаемые от Jira данные в необходимые для отображения свойства и собирает коллекцию объектов.
 *
 */

namespace GanttChartBundle\Service;

use Exception;
use GanttChartBundle\Model\JiraItemDecorator;
use GanttChartBundle\Repository\JiraRepository;
use GanttChartBundle\Model\JiraItem;
use JiraRestApi\Configuration\ArrayConfiguration;
use Symfony\Component\Validator\Constraints\DateTime;

class JiraRepositoryService
{

    /**
     * @var \DateTime
     */
    private $collectionPeriodStartDate;

    /**
     * @var \DateTime
     */
    private $collectionPeriodEndDate;

    /**
     * @var jiraItem
     */
    private $jiraCollection;

    /**
     * @var JiraRepository
     */
    protected $jiraRepository;

    /**
     * JiraRepositoryService constructor.
     */
    public function __construct()
    {
        $this->jiraRepository = new JiraRepository();
    }

    /**
     * @return ArrayConfiguration
     */
    public function getJiraConfig()
    {
        return $this->jiraRepository->getJiraConfig();
    }

    /**
     * @param $repositoryMethod string
     * @return mixed
     */
    public function getJiraCollection($repositoryMethod, $params = NULL)
    {
        $this->createJiraItemCollection($repositoryMethod, $params);
        return $this->jiraCollection;
    }
    
    /**
     * @param $jiraTask
     * @return JiraItem
     */
    private function convertJiraToItem($jiraTask)
    {
        $item = new JiraItem();

        $item->setId($jiraTask->id);
        $item->setKey($jiraTask->key);
        $item->setName($jiraTask->fields->summary);
        $item->setAssignee($jiraTask->fields->assignee);
        $item->setStatus($jiraTask->fields->status->name);

        if(isset($jiraTask->fields->timeoriginalestimate)) {
            $item->setTimeEstimate($jiraTask->fields->timeoriginalestimate->scalar);
        }

        $item->setTimeSpent($jiraTask->fields->timespent);

        $item->setCreateDate($jiraTask->fields->created);

        $item = $this->setItemStartDateByJiraTask($item, $jiraTask);

        $item = $this->setItemDueDateByJiraTask($item, $jiraTask);

        // @TODO вынести
        $item = $this->itemPostProcessor($item, $jiraTask);

        return $item;
    }

    /**
     * @param $repositoryMethod string
     * @throws Exception
     * @return array
     */
    private function createJiraItemCollection($repositoryMethod, $params)
    {

        if(method_exists($this->jiraRepository, $repositoryMethod) === false) {
            throw new Exception("Вызывается несуществующий метод репозитория Jira");
        }
        // @TODO времнка динамический вызов метода репозитория
        $jiraRepositoryEntity = $this->jiraRepository->{ $repositoryMethod }($params);

        foreach ($jiraRepositoryEntity as $key => $jiraTask) {

            $item = $this->convertJiraToItem($jiraTask);

            $this->setCollectionPeriodEndDate($item->getDueDate(), $jiraTask);

            $this->setCollectionPeriodStartDate($item->getStartDate(), $item);

            $this->jiraCollection[] = $item;

        }

        return $this->jiraCollection;
    }

    /**
     * @param JiraItem $item
     * @param $jiraTask
     * @return JiraItem
     * @throws Exception
     */
    private function itemPostProcessor(JiraItem $item, $jiraTask): JiraItem
    {
        $starDate = $item->getStartDate()->getTimestamp();

        $endDate = new \DateTime($item->getDueDate()->format('Y-m-d'));
        $endDate->modify('23:59:59');

        if($starDate > $endDate->getTimestamp()) {
            $item->setViewStatus($item::WARNING);
        }

        return $item;
    }

    /**
     * @param JiraItem $item
     * @param $jiraTask
     * @return JiraItem
     * Выбирает дату завершения и устанавливает статус для отображения
     * @throws Exception
     */
    private function setItemDueDateByJiraTask(JiraItem $item, $jiraTask)
    {
        $item->setDueDate(new \DateTime($jiraTask->fields->duedate));
        $item->setViewStatus($item::PROGRESS);

        if($item->getDueDate() === NULL) {
            $item->setViewStatus($item::WARNING);

            // Задача в статусе планируется
            if( (int)$jiraTask->fields->status->id === JiraRepository::PLANNING_TASK_ID) {
                $item->setViewStatus($item::IMPORTANT);
                $item->setDueDate(new \DateTime());
            }

            // Задача в статусе новая
            if( (int)$jiraTask->fields->status->id === JiraRepository::NEW_TASK_ID) {
                $item->setViewStatus($item::NORMAL);
                $item->setDueDate(new \DateTime());
            }
        }

        // Устанавливаем дату из FixVersion
        if($item->getDueDate() === NULL) {

            $date = new \DateTime();
            $item->setDueDate($date->modify('+1 day'));
            $item->setViewStatus($item::WARNING);

            if(is_array($jiraTask->fields->fixVersions) === true) {
                $fixVersion = array_pop($jiraTask->fields->fixVersions);

                if(isset($fixVersion->releaseDate)) {
                    $item->setDueDate(new \DateTime($fixVersion->releaseDate));
                }
            }
        }

        return $item;
    }

    /**
     * @param JiraItem $item
     * @param $jiraTask
     * @return JiraItem
     * Устанавливает дату начала исполнения задачи
     */
    private function setItemStartDateByJiraTask(JiraItem $item, $jiraTask)
    {
        $date = $jiraTask->fields->updated;
        $item->setStartDate($date);

        return $item;
    }

    /**
     * @return \DateTime
     */
    public function getCollectionPeriodStartDate()
    {
        return $this->collectionPeriodStartDate;
    }

    /**
     * @param \DateTime $date
     */
    private function setCollectionPeriodStartDate(\DateTime $date, JiraItem $item)
    {
        if($this->collectionPeriodStartDate === NULL) {
            $this->collectionPeriodStartDate = new \DateTime($date->format('Y-m-d'));
        }

        if($this->collectionPeriodStartDate->getTimestamp() >= $date->getTimestamp()) {
            $this->collectionPeriodStartDate = new \DateTime($date->format('Y-m-d'));
        }
    }

    /**
     * @return \DateTime
     */
    public function getCollectionPeriodEndDate()
    {
        return $this->collectionPeriodEndDate;
    }

    /**
     * @param \DateTime $date
     */
    private function setCollectionPeriodEndDate(\DateTime $date, $jiraTask)
    {
        if($this->collectionPeriodEndDate === NULL) {
            $this->collectionPeriodEndDate = new \DateTime($date->format('Y-m-d'));
        }

        if($this->collectionPeriodEndDate->getTimestamp() <= $date->getTimestamp()) {
            $this->collectionPeriodEndDate = new \DateTime($date->format('Y-m-d'));
        }

        // Костыль, когда обновление произошло позже dueDate
        if($this->collectionPeriodEndDate->getTimestamp() <= $jiraTask->fields->updated->getTimestamp()) {
            $this->collectionPeriodEndDate = $jiraTask->fields->updated;
        }
    }

}