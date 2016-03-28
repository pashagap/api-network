<?php

namespace Hatch\Inspections\Model\Entities\WorkOrder;

use Hatch\Core\Model\Common\RepositoryCrudInterface;

/**
 * Interface WorkOrderRepositoryInterface
 *
 * @package Hatch\Inspections\Model\Entities\WorkOrder
 */
interface WorkOrderRepositoryInterface extends RepositoryCrudInterface
{
    /**
     * @param mixed $id
     *
     * @return array
     */
    function readCommentsById($id);

    /**
     * @param mixed $id
     * @param array $data
     *
     * @return array
     */
    function createCommentToId($id, array $data = []);
}
