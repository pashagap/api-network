<?php

namespace Hatch\SocialNetwork\Middleware;

use Hatch\Core\Middleware\AbstractRequestValidator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class PostsRequestValidator
 *
 * @package Hatch\SocialNetwork\Middleware
 */
class PostsRequestValidator extends AbstractRequestValidator
{
    public function validateCreateRequest(Request $request)
    {
        $data = $request->request->all();

        $constraints = new Assert\Collection(
            [
                'text' => new Assert\NotBlank()
            ]
        );
        $this->validateValue($data, $constraints);
    }

    public function validateUpdateRequest(Request $request)
    {
        $data = $request->request->all();

        $constraints = new Assert\Collection(
            [
                'text' => new Assert\NotBlank()
            ]
        );
        $this->validateValue($data, $constraints);
    }

    public function validateCommentCreateRequest(Request $request)
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
}