<?php

namespace App\Integrations\Salesforce;

use App\Models\SalesForce;
use Illuminate\Support\Facades\Http;

class SalesforceClient {

    public function connect()
    {
        $salesForce = SalesForce::first();

        if (!empty($salesForce->sf_refresh_token)) {
            $tokenBaseUrl = rtrim($salesForce->login_uri ?: config('services.salesforce.login_url'), '/');

            $response = Http::asForm()->post($tokenBaseUrl . '/services/oauth2/token', [
                'grant_type' => 'refresh_token',
                'refresh_token' => $salesForce->sf_refresh_token,
                'client_id' => $salesForce->client_id ?: config('services.salesforce.consumer_key'),
                'client_secret' => $salesForce->client_secret ?: config('services.salesforce.consumer_secret'),
            ]);

            if ($response->successful()) {
                $data = $response->json();
                // Fix: Extract just the user ID from 'id' field
                $idParts = explode('/', $data['id'] ?? '');
                $userId = end($idParts);

                // Save to DB
                $salesForce->update([
                    'sf_access_id'     => $userId,
                    'sf_access_token'  => $data['access_token'],
                    'sf_signature'     => $data['signature'] ?? null,
                    'sf_issued_at'     => $data['issued_at'] ?? now(),
                    'sf_instance_url'  => $data['instance_url'] ?? $salesForce->sf_instance_url,
                ]);
            } else {
                throw new \Exception("Salesforce token refresh failed: " . $response->body());
            }
        }

        return true;
    }
}
