<?php

namespace App\Services;

class ResponseService
{
    /**
     * Return a success response.
     *
     * @param mixed $data
     * @param string $message
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function success($data = null, string $message = "Success", int $statusCode = 200)
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data,
            'status_code' => $statusCode
        ], $statusCode);
    }

    /**
     * Return an error response.
     *
     * @param string $message
     * @param int $statusCode
     * @param mixed $error
     * @return \Illuminate\Http\JsonResponse
     */
    public function error(string $message = "An error occurred", int $statusCode = 400, $error = null)
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'error' => $error,
            'status_code' => $statusCode
        ], $statusCode);
    }
}
