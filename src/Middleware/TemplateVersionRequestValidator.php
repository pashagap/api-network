<?php

namespace Hatch\Inspections\Middleware;

use Hatch\Core\Middleware\AbstractRequestValidator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class TemplateVersionRequestValidator
 *
 * @package Hatch\Inspections\Middleware
 */
class TemplateVersionRequestValidator extends AbstractRequestValidator
{
    public function validateCreateRequest(Request $request)
    {
        $data = $request->request->all();

        $templateVersionState = $this->sysApp['inspections.templateVersion.state'];
        $questionType = $this->sysApp['inspections.question.type'];

        $constraints = new Assert\Collection(
            [
                'template' => new Assert\NotBlank(),
                'state' => [
                    new Assert\Optional(
                        new Assert\Choice(
                            [
                                'choices' => [
                                    $templateVersionState::DRAFT,
                                    $templateVersionState::PUBLISHED
                                ]
                            ]
                        )
                    )
                ],
                'questions' => [
                    new Assert\Optional(
                        new Assert\All(
                            [
                                'constraints' => [
                                    new Assert\NotBlank(),
                                    new Assert\Collection(
                                        [
                                            'question' => [
                                                new Assert\NotBlank()
                                            ],
                                            'type' => [
                                                new Assert\Optional(
                                                    new Assert\Choice(
                                                        [
                                                            'choices' => [
                                                                $questionType::EMPTY_CONTENT
                                                            ]
                                                        ]
                                                    )
                                               )
                                            ],
                                            'responses' => [
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

        $templateVersionState = $this->sysApp['inspections.templateVersion.state'];
        $questionType = $this->sysApp['inspections.question.type'];

        $constraints = new Assert\Collection(
            [
                'state' => [
                    new Assert\Optional(
                        new Assert\Choice(
                            [
                                'choices' => [
                                    $templateVersionState::PUBLISHED,
                                    $templateVersionState::UNPUBLISHED
                                ]
                            ]
                        )
                    )
                ],
                'questions' => [
                    new Assert\Optional(
                        new Assert\All(
                            [
                                'constraints' => [
                                    new Assert\NotBlank(),
                                    new Assert\Collection(
                                        [
                                            'question' => [
                                                new Assert\NotBlank()
                                            ],
                                            'type' => [
                                                new Assert\Optional(
                                                    new Assert\Choice(
                                                        [
                                                            'choices' => [
                                                                $questionType::EMPTY_CONTENT
                                                            ]
                                                        ]
                                                    )
                                                )
                                            ],
                                            'responses' => [
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

    public function validateReadSimpleTemplateVersionReport(Request $request)
    {
        $data = $request->query->all();

        $constraints = new Assert\Collection(
            [
                'createdDate' => new Assert\Optional(
                    new Assert\Callback($this->getDateTimeValidationCallback())
                ),
                'modifiedDate' => new Assert\Optional(
                    new Assert\Callback($this->getDateTimeValidationCallback())
                ),
                'templateId' => new Assert\Optional(
                    new Assert\NotBlank()
                ),
                'state' => new Assert\Optional(
                    new Assert\NotBlank()
                )
            ]
        );
        $this->validateValue($data, $constraints);
    }
}
