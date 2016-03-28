<?php

namespace Hatch\Inspections\Middleware;

use Hatch\Core\Middleware\AbstractRequestValidator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class AnswerRequestValidator
 *
 * @package Hatch\Inspections\Middleware
 */
class AnswerRequestValidator extends AbstractRequestValidator
{
    public function validateCreateRequest(Request $request)
    {
        $data = $request->request->all();

        $questionOutcomeType = $this->sysApp['inspections.question.outcomeType'];

        $constraints = new Assert\Collection(
            [
                'question' => new Assert\NotBlank(),
                'inspection' => new Assert\NotBlank(),
                'outcome' => [
                    new Assert\Optional(
                        new Assert\Choice(
                            [
                                'choices' => [
                                    $questionOutcomeType::PASS,
                                    $questionOutcomeType::FAIL,
                                    $questionOutcomeType::NOT_APPLICABLE
                                ]
                            ]
                        )
                    )
                ],
                'content' => [
                    new Assert\Optional(
                        new Assert\All(
                            [
                                'constraints' => [
                                    new Assert\Collection(
                                        [
                                            'value' => new Assert\NotBlank()
                                        ]
                                    )
                                ]
                            ]
                        )
                    )
                ]
            ]
        );
        $this->validateValue($data, $constraints);
    }

    public function validateUpdateRequest(Request $request)
    {
        $data = $request->request->all();

        $questionOutcomeType = $this->sysApp['inspections.question.outcomeType'];

        $constraints = new Assert\Collection(
            [
                'outcome' => [
                    new Assert\Optional(
                        new Assert\Choice(
                            [
                                'choices' => [
                                    $questionOutcomeType::PASS,
                                    $questionOutcomeType::FAIL,
                                    $questionOutcomeType::NOT_APPLICABLE
                                ]
                            ]
                        )
                    )
                ],
                'content' => [
                    new Assert\Optional(
                        new Assert\All(
                            [
                                'constraints' => [
                                    new Assert\Collection(
                                        [
                                            'value' => new Assert\NotBlank()
                                        ]
                                    )
                                ]
                            ]
                        )
                    )
                ]
            ]
        );
        $this->validateValue($data, $constraints);
    }
}
