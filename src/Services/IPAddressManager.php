<?php

namespace Siyaludeio\IPAccessGuard\Services;

use Illuminate\Support\Facades\Http;

class IPAddressManager
{
    public string $ipAddressesFilePath;

    public function __construct()
    {
        $this->ipAddressesFilePath = config('ip-access-guard.file_path') . '/' . config('ip-access-guard.file_name');
    }

    public function setIPAddressesToFile(array $ipAddresses): void
    {
        $encryptedIPAddresses = encrypt(json_encode($ipAddresses));
        file_put_contents($this->ipAddressesFilePath, $encryptedIPAddresses);
    }

    public function getIPAddressesFromFile(): array
    {
        if (!file_exists($this->ipAddressesFilePath)) {
            return [];
        }

        $encryptedIPAddresses = file_get_contents($this->ipAddressesFilePath);
        $decryptedIPAddresses = decrypt($encryptedIPAddresses);
        return json_decode($decryptedIPAddresses, true);
    }


    public function verifyIp(): bool
    {
        $userIpAddress = request()->ip();
        $ipAddresses = $this->getIPAddressesFromFile();
        if (!in_array($userIpAddress, $ipAddresses)) {
            if ($this->getIPAddressesFromRemote()) {
                $ipAddresses = $this->getIPAddressesFromFile();
                if (!in_array($userIpAddress, $ipAddresses)) {
                    return false;
                }
            } else {
                return false;
            }
        }
        return true;
    }

    public function getIPAddressesFromRemote(): bool
    {
        $response = Http::get(config('ip-access-guard.remote_api_url'));
        if (!$response->ok()) {
            return false;
        }
        $ipAddresses = $response->json();
        $this->setIPAddressesToFile($ipAddresses);
        return true;
    }

}
