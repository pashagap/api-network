<?php

namespace Hatch\Inspections\Middleware;

use Hatch\Core\Middleware\AbstractRequestValidator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class TemplateRequestValidator
 *
 * @package Hatch\Inspections\Middleware
 */
class TemplateRequestValidator extends AbstractRequestValidator
{
    public function validateCreateRequest(Request $request)
    {
        $data = $request->request->all();

        $constraints = new Assert\Collection(
            [
                'name' => [
                    new Assert\NotBlank()
                ],
                'description' => [
                    new Assert\Optional(
                        new Assert\NotBlank()
                    )
                ],
                'tags' => new Assert\Optional(
                    new Assert\Type(
                        ['type' => 'array']
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
                'name' => [
                    new Assert\Optional(
                        new Assert\NotBlank()
                    )
                ],
                'description' => [
                    new Assert\Optional(
                        new Assert\NotBlank()
                    )
                ],
                'tags' => new Assert\Optional(
                    new Assert\Type(
                        ['type' => 'array']
                    )
                )
            ]
        );
        $this->validateValue($data, $constraints);
    }

    public function validateReadSimpleTemplateReport(Request $request)
    {
        $data = $request->query->all();

        $constraints = new Assert\Collection(
            [
                'createdDate' => new Assert\Optional(
                    new Assert\Callback($this->getDateTimeValidationCallback())
                ),
                'modifiedDate' => new Assert\Optional(
                    new Assert\Callback($this->getDateTimeValidationCallback())
                )
            ]
        );
        $this->validateValue($data, $constraints);
    }
}
