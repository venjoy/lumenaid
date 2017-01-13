<?php

namespace Venjoy\Lumenaid;

class RestResponse
{
    public function success($data = '', $message = '') 
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ], 200);
    }

    public function failed($exception = '', $message = '') 
    {
        return response()->json([
            'status' => 'failed',
            'message' => $message,
            'error' => $exception->getMessage()
        ], 400);
    }
}