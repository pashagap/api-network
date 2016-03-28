<?php

namespace Hatch\Inspections\Model\Services;

use Hatch\Core\Model\Common\AbstractService;
use InspectionStatsMcs\Processor;

/**
 * Class StatisticService
 *
 * @package Hatch\Inspections\Model\Services
 */
class StatisticService extends AbstractService
{
    /**
     * @return Processor
     */
    public function getStatisticTool()
    {
        return $this->sysApp['hatch-is.insp-stats-mcs.processor'];
    }

    public function readInspectionsDashboard($locationId)
    {
        $tool = $this->getStatisticTool();

        return [
            'response' => [
                'data' => $tool->getInspectionsDashboard($locationId)
            ],
            'status' => 200,
            'headers' => [],
        ];
    }

    public function readInspectionsStatistic($locationId)
    {
        $tool = $this->getStatisticTool();

        return [
            'response' => [
                'data' => $tool->getInspectionsStatistic($locationId)
            ],
            'status' => 200,
            'headers' => []
        ];
    }

    public function readWorkOrdersDashboard($locationId)
    {
        $tool = $this->getStatisticTool();

        return [
            'response' => [
                'data' => $tool->getWorkOrdersDashboard($locationId)
            ],
            'status' => 200,
            'headers' => []
        ];
    }

    /**
     * @param $locationId
     * @param \DateTime $createdDate
     * @param \DateTime $modifiedDate
     *
     * @return array
     */
    public function readSimpleTemplateReport($locationId, $createdDate = null, $modifiedDate = null)
    {
        $tool = $this->getStatisticTool();

        return [
            'response' => [
                'data' => $tool->getSimpleTemplateReport($locationId, $createdDate, $modifiedDate)
            ],
            'status' => 200,
            'headers' => []
        ];
    }

    /**
     * @param $locationId
     * @param string $state
     * @param \DateTime $date
     *
     * @return array
     */
    public function readInspectionReport($locationId, $state, $date)
    {
        $tool = $this->getStatisticTool();

        return [
            'response' => [
                'data' => $tool->getInspectionReport($locationId, $state, $date)
            ],
            'status' => 200,
            'headers' => []
        ];
    }

    /**
     * @param $locationId
     * @param null $templateId
     * @param \DateTime $createdDate
     * @param \DateTime $modifiedDate
     * @param null $state
     * @return array
     */
    public function readSimpleTemplateVersionReport(
        $locationId,
        $templateId = null,
        $createdDate = null,
        $modifiedDate = null,
        $state = null
    )
    {
        $tool = $this->getStatisticTool();

        return [
            'response' => [
                'data' => $tool->getSimpleTemplateVersionReport(
                    $locationId,
                    $templateId,
                    $createdDate,
                    $modifiedDate,
                    $state
                )
            ],
            'status' => 200,
            'headers' => []
        ];
    }

    public function readSimpleWorkOrderReport(
        $locationId,
        $createdDate = null,
        $completedDate = null,
        $state = null
    )
    {
        $tool = $this->getStatisticTool();

        return [
            'response' => [
                'data' => $tool->getSimpleWorkOrderReport(
                    $locationId,
                    $createdDate,
                    $completedDate,
                    $state
                )
            ],
            'status' => 200,
            'headers' => []
        ];
    }
}
