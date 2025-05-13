<?php

class JsonResponse
{
    public static function success($data = null, ?string $message = null, int $status_code = 200, array $meta = null)
    {
        $response = ['status' => 'success'];
        if ($data !== null)
        {
            $response['data'] = $data;
        }
        if ($message !== null)
        {
            $response['message'] = $message;
        }
        if ($meta !== null)
        {
            $response['meta'] = $meta;
        }
        return self::json($response, $status_code);
    }
    
    public static function error(?string $message = null, int $status_code = 400, $errors = null, array $meta = null)
    {
        $response = ['status' => 'error'];
        if ($message !== null)
        {
            $response['message'] = $message;
        }
        if ($errors !== null)
        {
            $response['errors'] = $errors;
        }
        if ($meta !== null)
        {
            $response['meta'] = $meta;
        }
        return self::json($response, $status_code);
    }
    
    public static function json($data, int $status_code = 200, array $headers = [])
    {
        $headers['Content-Type'] = 'application/json';
        http_response_code($status_code);
        foreach ($headers as $name => $value)
        {
            header("$name: $value");
        }
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }
}
