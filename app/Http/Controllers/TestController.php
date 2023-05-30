<?php

namespace App\Http\Controllers;


class TestController
{
    public function test($query){
        $query = [
            "q" => "Coffee",
            "location" => "Austin, Texas, United States",
            "hl" => "en",
            "gl" => "us",
            "google_domain" => "google.com",
        ];

        $search = new GoogleSearch('secret_api_key');
        $result = $search->get_json($query);
    }


}
