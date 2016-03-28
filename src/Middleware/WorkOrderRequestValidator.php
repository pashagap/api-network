<?php

namespace Hatch\Inspections\Middleware;

use Hatch\Core\Middleware\AbstractRequestValidator;
use Hatch\Inspections\Model\Entities\WorkOrder\WorkOrderState;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class WorkOrderRequestValidator
 *
 * @package Hatch\Inspections\Middleware
 */
class WorkOrderRequestValidator extends AbstractRequestValidator
{
    public function validateCreateRequest(Request $request)
    {
        $data = $request->request->all();

        /** @var WorkOrderState $workOrderState */
        $workOrderState = $this->sysApp['inspections.workOrder.state'];

        $constraints = new Assert\Collection(
            [
                'question' => new Assert\NotBlank(),
                'inspection' => new Assert\NotBlank(),
                'answer' => new Assert\NotBlank(),
                'issue' => new Assert\Optional(
                    new Assert\NotBlank()
                ),
                'comments' => new Assert\Optional(
                    new Assert\All(
                        new Assert\Collection(
                            [
                                'message' => new Assert\Optional(
                                    new Assert\NotBlank()
                                ),
                                'images' => new Assert\Optional(
                                    new Assert\All(
                                        new Assert\Collection(
                                            [
                                                'url' => new Assert\NotBlank(),
                                                'public_id' => new Assert\NotBlank()
                                            ]
                                        )
                                    )
                                )
                            ]
                        )
                    )
                ),
                'images' => new Assert\Optional(
                    new Assert\All(
                        new Assert\Collection(
                            [
                                'url' => new Assert\NotBlank(),
                                'public_id' => new Assert\NotBlank()
                            ]
                        )
                    )
                ),
                'state' => new Assert\Optional(
                    new Assert\Choice(
                        [
                            'choices' => [
                                $workOrderState::COMPLETED,
                                $workOrderState::INCOMPLETE,
                                $workOrderState::STARTED
                            ]
                        ]
                    )
                ),
                'assigned' => new Assert\Optional(
                    new Assert\NotBlank()
                )
            ]
        );
        $this->validateValue($data, $constraints);
    }

    public function validateUpdateRequest(Request $request)
    {
        $data = $request->request->all();

        /** @var WorkOrderState $workOrderState */
        $workOrderState = $this->sysApp['inspections.workOrder.state'];

        $constraints = new Assert\Collection(
            [
                'issue' => new Assert\Optional(
                    new Assert\NotBlank()
                ),
                'images' => new Assert\Optional(
                    new Assert\All(
                        new Assert\Collection(
                            [
                                'url' => new Assert\NotBlank(),
                                'public_id' => new Assert\NotBlank()
                            ]
                        )
                    )
                ),
                'state' => new Assert\Optional(
                    new Assert\Choice(
                        [
                            'choices' => [
                                $workOrderState::COMPLETED,
                                $workOrderState::INCOMPLETE,
                                $workOrderState::STARTED
                            ]
                        ]
                    )
                ),
                'assigned' => new Assert\Optional(
                    new Assert\NotBlank()
                )
            ]
        );
        $this->validateValue($data, $constraints);
    }

    public function createCommentToWorkOrderById(Request $request)
    {
        $data = $request->request->all();

        $constraints = new Assert\Collection(
            [
                'message' => new Assert\Optional(
                    new Assert\NotBlank()
                ),
                'images' => new Assert\Optional(
                    new Assert\All(
                        new Assert\Collection(
                            [
                                'url' => new Assert\NotBlank(),
                                'public_id' => new Assert\NotBlank()
                            ]
                        )
                    )
                )
            ]
        );

        $this->validateValue($data, $constraints);
    }

    public function validateReadSimpleWorkOrderReport(Request $request)
    {
        $data = $request->query->all();

        $constraints = new Assert\Collection(
            [
                'createdDate' => new Assert\Optional(
                    new Assert\Callback($this->getDateTimeValidationCallback())
                ),
                'completedDate' => new Assert\Optional(
                    new Assert\Callback($this->getDateTimeValidationCallback())
                ),
                'state' => new Assert\Optional(
                    new Assert\NotBlank()
                )
            ]
        );
        $this->validateValue($data, $constraints);
    }
}
