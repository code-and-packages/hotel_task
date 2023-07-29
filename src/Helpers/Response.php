<?php


namespace Src\Helpers;


class Response
{
    public static function json($data, $message = "", $status = 200, $headers = [])
    {
        // Set the Content-Type header to JSON
        $headers['Content-Type'] = 'application/json';

        $data = [
            "data" => $data,
            "message" => $message,
            "status" =>  $status
        ];

        // Encode the data into a JSON string
        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        // Send the HTTP status code
        http_response_code($status);

        // Set the headers
        foreach ($headers as $key => $value) {
            header("$key: $value");
        }

        // Output the JSON data
        echo $json;

        // Terminate the script (optional, to prevent further execution)
        exit;
    }
}