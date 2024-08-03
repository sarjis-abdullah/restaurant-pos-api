<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PosException extends Exception
{
    public $message;
    public $code;
    public $errors;

    public function __construct($errors, $code = 422, $message = 'Invalid data')
    {
        $this->message = $message;
        $this->code = $code;
        $this->errors = $errors;
        parent::__construct($message, $code);
    }

    public function render(Request $request): JsonResponse
    {
        return response()->json([
            'message' => $this->getMessage(),
            'errors' => $this->errors,
        ], $this->code);
    }
}

