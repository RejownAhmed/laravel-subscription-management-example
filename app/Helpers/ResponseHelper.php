<?php

if (!function_exists('success_response')) {
    function success_response($message = '', $data = null, $code = 200): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'result' => $data,
        ], $code);
    }

    if (!function_exists('error_response')) {
        function error_response($message = '', $data = null, $code = 400): \Illuminate\Http\JsonResponse
        {
            return response()->json([
                'status' => false,
                'message' => $message,
                'result' => $data,
            ], $code);
        }
    }

}
