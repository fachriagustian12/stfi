<?php
    
namespace App\Helpers;


class GeneralHelpers
{
    public function post($endpoint, $data, $method = 'POST'){
        set_time_limit(500);
        $url = "192.168.5.9/signage/public/".$endpoint;
        $ch = curl_init($url);

        $options = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
            ],
        ];

        if ($method == 'POST' || $method == 'PUT') {
            $options[CURLOPT_POSTFIELDS] = json_encode($data);
        }
        
        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);

        if ($response === false) {
            echo 'Curl error: ' . curl_error($ch);
        }

        curl_close($ch);

        return json_decode($response, true);
    }
}
?>