<?php

namespace GanttChartBundle\Controller;

use GanttChartBundle\Model\ChartPeriod;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use GanttChartBundle\Lib\Gantt\Gantt;
use GanttChartBundle\Service\JiraRepositoryService;
use Symfony\Component\HttpFoundation\Response;


class DefaultController extends Controller
{

    /**
     * @Route("/gantt", name="Jira request")
     * @param Request $request
     * @return Response
     */
    public function ganttAction(Request $request)
    {
        //TODO Вынести работу с формой в формы фреймворка
        $query = $request->query->get('q', []);

        // Title для таблички
        $title = 'JQL Query';
        if(array_key_exists('assignee', $query)) {
            $title = $query['assignee'];
        }

        $default = array(
            'assignee'  => '',
            'jql'       => '',
            'limit'     => 10,
        );
        $query = array_merge($default, $query);

        $jiraService = new JiraRepositoryService($this->container->getParameterBag());
        $jiraItems = $jiraService->getJiraCollection('jiraGetByJqlSearch', $query);
        $startDate = $jiraService->getCollectionPeriodStartDate();
        $endDate   = $jiraService->getCollectionPeriodEndDate();
        $jiraConfig = $jiraService->getJiraConfig();

        $chartPeriod = new ChartPeriod($startDate, $endDate);

        return $this->render('GanttChartBundle:Default:chart.html.twig', [
            'query'          => $query,
            'title'          => $title,
            'chartPeriod'    => $chartPeriod,
            'chartItems'     => $jiraItems,
            'jiraConfig'     => $jiraConfig,
        ]);
    }

    /**
     * @Route("/", name="Home page")
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        return $this->render('GanttChartBundle:Default:index.html.twig', []);
    }

}
