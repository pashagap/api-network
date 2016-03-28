<?php

namespace Hatch\Inspections\Model\Entities\Inspection;

use Hatch\Core\Model\Common\RepositoryCrudInterface;

/**
 * Interface InspectionRepositoryInterface
 *
 * @package Hatch\Inspections\Model\Entities\Inspection
 */
interface InspectionRepositoryInterface extends RepositoryCrudInterface
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
