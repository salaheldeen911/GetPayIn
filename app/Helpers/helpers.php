<?php

if (! function_exists('authUser')) {
    function authUser()
    {
        return auth('sanctum')->check() ? auth('sanctum')->user() : null;
    }
}

if (! function_exists('respondWithToken')) {
    function respondWithToken($token, $message = null, $data = [])
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'access_token' => $token,
            'token_type' => 'Bearer',
            'data' => $data,
        ]);
    }
}

if (! function_exists('respondWithError')) {
    function respondWithError($message, $errors = [], $code = 400)
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'errors' => $errors,
        ], $code);
    }
}

if (! function_exists('respondWithSuccess')) {
    function respondWithSuccess($message, $data = [], $code = 200)
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ], $code);
    }
}
