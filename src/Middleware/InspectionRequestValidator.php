<?php

namespace Hatch\Inspections\Middleware;

use Hatch\Core\Middleware\AbstractRequestValidator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class InspectionRequestValidator
 *
 * @package Hatch\Inspections\Middleware
 */
class InspectionRequestValidator extends AbstractRequestValidator
{
    public function validateCreateRequest(Request $request)
    {
        $data = $request->request->all();

        $constraints = new Assert\Collection(
            [
                'templateVersion' => [
                    new Assert\NotBlank()
                ],
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

    public function validateUpdateRequest(Request $request)
    {
        $data = $request->request->all();

        $constraints = new Assert\Collection(
            [
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

    public function createCommentToInspectionById(Request $request)
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

    public function validateInspectionReport(Request $request)
    {
        $data = $request->query->all();

        $constraints = new Assert\Collection(
            [
                'date' => new Assert\Optional(
                    new Assert\Callback($this->getDateTimeValidationCallback())
                )
            ]
        );
        $this->validateValue($data, $constraints);
    }
}

