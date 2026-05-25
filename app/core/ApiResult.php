<?php

class ApiResult
{
    public int $code;
    public string $message;

    public function __construct(int $code, string $message = "")
    {
        $this->code = $code;
        $this->message = $message;
    }

    public static function success($message = "OK")
    {
        return new ApiResult(1, $message);
    }

    public static function error($message = "Error")
    {
        return new ApiResult(0, $message);
    }

    public function toArray()
    {
        return [
            "code" => $this->code,
            "message" => $this->message
        ];
    }

    public function toJson()
    {
        header('Content-Type: application/json');
        echo json_encode([
            "code" => $this->code,
            "message" => $this->message
        ]);
    }
}
