<?php


namespace App\Http\Controllers\Api\Admin;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Token;

class WorkstreamController extends Controller
{
    /**
     * Refresh the token.
     *
     * @return string|null The new token or null if the refresh fails.
     */
    private function refreshToken()
{
    Log::info('Attempting to refresh token...');

    $response = Http::asForm()->post(env('WORKSTREAM_REFRESH_URL'), [
        'grant_type' => 'client_credentials',
        'client_id' => env('WORKSTREAM_CLIENT_ID'),
        'client_secret' => env('WORKSTREAM_CLIENT_SECRET'),
        'token' => env('WORKSTREAM_TOKEN'),
    ]);

    if ($response->successful()) {
        $data = $response->json();
        $newToken = $data['token'];

        Log::info('Token refreshed successfully.', ['token' => $newToken]);

        // Update or create the token in the database
        Token::updateOrCreate(
            ['name' => 'workstream'],
            [
                'token' => $newToken,
                'expires_at' => now()->addSeconds($data['expires_in']),
            ]
        );

        Log::info('Token saved to database successfully.');
        return $newToken;
    }

    Log::error('Failed to refresh token.', ['response' => $response->json()]);
    return null;
}

    /**
     * Get the current token from the database.
     *
     * @return string|null
     */
    private function getToken()
    {
        $token = Token::where('name', 'workstream')->first();

        if ($token && $token->expires_at > now()) {
            Log::info('Using existing token from the database.');
            return $token->token;
        }

        Log::info('Token expired or not found. Refreshing token...');
        return $this->refreshToken();
    }

    /**
     * Get all published positions.
     */
    public function getPositions()
    {
        $token = $this->getToken();

        if (!$token) {
            return response()->json(['error' => 'Failed to fetch token.'], 500);
        }

        $response = Http::withToken($token)
            ->get(env('WORKSTREAM_POSITIONS_URL'), [
                'status' => 'published',
            ]);

        if ($response->successful()) {
            return response()->json([
                'success' => true,
                'data' => $response->json(),
            ], 200);
        }

        Log::error('Failed to fetch positions.', ['response' => $response->json()]);
        return response()->json(['error' => 'Failed to fetch positions.'], 500);
    }
}
