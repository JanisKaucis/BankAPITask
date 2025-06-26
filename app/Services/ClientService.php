<?php
namespace App\Services;

use App\Http\Resources\AccountResource;
use App\Models\Client;

class ClientService
{
    public function getAccounts($id)
    {
        $client = Client::with('accounts')->find($id);
        if (!$client) return response()->json(['error' => 'Client not found'], 404);
        return AccountResource::collection($client->accounts);
    }
}
