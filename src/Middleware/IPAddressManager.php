<?php

namespace Siyaludeio\IPAccessGuard\Middleware;

use Illuminate\Support\Facades\Http;

class IPAddressManager
{
    public static function getIPAddresses()
    {
        $localFilePath = public_path('path/ip-addresses.json');

        if (file_exists($localFilePath) && time() - filemtime($localFilePath) > 60) {
            // Fetch IP addresses from the remote API
            $response = Http::get(config('ip-access-guard.remote_api_url'));
            $ipAddresses = $response->json();

            // Save IP addresses to the local file
            file_put_contents($localFilePath, json_encode($ipAddresses));
        } else {

            // Load IP addresses from the local file
            $ipAddresses = json_decode(file_get_contents($localFilePath), true);
        }

        return $ipAddresses;
    }
}
