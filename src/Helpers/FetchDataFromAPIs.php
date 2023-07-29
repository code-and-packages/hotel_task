<?php

namespace Src\Helpers;

class FetchDataFromAPIs
{
    public function Get($url)
    {
        try {
            $content = @file_get_contents($url);
            if ($content === false) {
                throw new \Exception("Failed to read the file.");
            }
            return json_decode($content, true);
        } catch (\Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }
}
