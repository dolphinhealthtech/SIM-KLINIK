<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\pasien;

class WhatsAppController extends Controller
{
    protected $whatsappApiUrl;

    public function __construct()
    {
        // URL API WhatsApp Gateway dari Node.js
        $this->whatsappApiUrl = config('services.whatsapp.api_url', 'http://localhost:3000/api');
    }

    /**
     * Get all WhatsApp sessions
     */
    public function getAllSessions()
    {
        try {
            $response = Http::timeout(10)->get("{$this->whatsappApiUrl}/sessions");

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch sessions',
                'status_code' => $response->status()
            ], $response->status());

        } catch (\Exception $e) {
            Log::error('WhatsApp API Error - getAllSessions: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'error' => 'Connection error to WhatsApp Gateway',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get specific session status
     */
    public function getSessionStatus($sessionId)
    {
        try {
            $response = Http::timeout(10)->get("{$this->whatsappApiUrl}/{$sessionId}/status");

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch session status',
                'session_id' => $sessionId,
                'status_code' => $response->status()
            ], $response->status());

        } catch (\Exception $e) {
            Log::error("WhatsApp API Error - getSessionStatus ({$sessionId}): " . $e->getMessage());

            return response()->json([
                'success' => false,
                'error' => 'Connection error to WhatsApp Gateway',
                'session_id' => $sessionId,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get QR Code for session login
     */
    public function getQRCode($sessionId)
    {
        try {
            $response = Http::timeout(10)->get("{$this->whatsappApiUrl}/{$sessionId}/qr");

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json([
                'success' => false,
                'error' => 'QR Code not available',
                'session_id' => $sessionId,
                'status_code' => $response->status()
            ], $response->status());

        } catch (\Exception $e) {
            Log::error("WhatsApp API Error - getQRCode ({$sessionId}): " . $e->getMessage());

            return response()->json([
                'success' => false,
                'error' => 'Connection error to WhatsApp Gateway',
                'session_id' => $sessionId,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get token information for session
     */
    public function getTokenInfo($sessionId)
    {
        try {
            $response = Http::timeout(10)->get("{$this->whatsappApiUrl}/{$sessionId}/token");

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch token info',
                'session_id' => $sessionId,
                'status_code' => $response->status()
            ], $response->status());

        } catch (\Exception $e) {
            Log::error("WhatsApp API Error - getTokenInfo ({$sessionId}): " . $e->getMessage());

            return response()->json([
                'success' => false,
                'error' => 'Connection error to WhatsApp Gateway',
                'session_id' => $sessionId,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Set premium token for session
     */
    public function setPremiumToken(Request $request, $sessionId)
    {
        $request->validate([
            'token' => 'required|string',
            'limit' => 'required|integer|min:1',
            'expiryHours' => 'nullable|integer|min:1',
            'active' => 'nullable|boolean'
        ]);

        try {
            $response = Http::timeout(10)->post("{$this->whatsappApiUrl}/{$sessionId}/token", [
                'token' => $request->token,
                'limit' => $request->limit,
                'expiryHours' => $request->expiryHours ?? 24,
                'active' => $request->active ?? true
            ]);

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json([
                'success' => false,
                'error' => 'Failed to set premium token',
                'session_id' => $sessionId,
                'status_code' => $response->status()
            ], $response->status());

        } catch (\Exception $e) {
            Log::error("WhatsApp API Error - setPremiumToken ({$sessionId}): " . $e->getMessage());

            return response()->json([
                'success' => false,
                'error' => 'Connection error to WhatsApp Gateway',
                'session_id' => $sessionId,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update token status (activate/deactivate)
     */
    public function updateTokenStatus(Request $request, $sessionId)
    {
        $request->validate([
            'active' => 'required|boolean'
        ]);

        try {
            $response = Http::timeout(10)->post("{$this->whatsappApiUrl}/{$sessionId}/token/status", [
                'active' => $request->active
            ]);

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json([
                'success' => false,
                'error' => 'Failed to update token status',
                'session_id' => $sessionId,
                'status_code' => $response->status()
            ], $response->status());

        } catch (\Exception $e) {
            Log::error("WhatsApp API Error - updateTokenStatus ({$sessionId}): " . $e->getMessage());

            return response()->json([
                'success' => false,
                'error' => 'Connection error to WhatsApp Gateway',
                'session_id' => $sessionId,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete/reset premium token
     */
    public function deleteToken($sessionId)
    {
        try {
            $response = Http::timeout(10)->delete("{$this->whatsappApiUrl}/{$sessionId}/token");

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json([
                'success' => false,
                'error' => 'Failed to delete token',
                'session_id' => $sessionId,
                'status_code' => $response->status()
            ], $response->status());

        } catch (\Exception $e) {
            Log::error("WhatsApp API Error - deleteToken ({$sessionId}): " . $e->getMessage());

            return response()->json([
                'success' => false,
                'error' => 'Connection error to WhatsApp Gateway',
                'session_id' => $sessionId,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send message
     */
    public function sendMessage(Request $request, $sessionId)
    {
        $request->validate([
            'number' => 'required|string',
            'message' => 'required|string',
            'token' => 'nullable|string'
        ]);

        try {
            $response = Http::timeout(30)->post("{$this->whatsappApiUrl}/{$sessionId}/send", [
                'number' => $request->number,
                'message' => $request->message,
                'token' => $request->token
            ]);

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json([
                'success' => false,
                'error' => 'Failed to send message',
                'session_id' => $sessionId,
                'status_code' => $response->status(),
                'response' => $response->json()
            ], $response->status());

        } catch (\Exception $e) {
            Log::error("WhatsApp API Error - sendMessage ({$sessionId}): " . $e->getMessage());

            return response()->json([
                'success' => false,
                'error' => 'Connection error to WhatsApp Gateway',
                'session_id' => $sessionId,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send template message
     */
    public function sendTemplateMessage(Request $request, $sessionId)
    {
        $request->validate([
            'number' => 'required|string',
            'template' => 'required|string',
            'templateData' => 'required|array',
            'token' => 'nullable|string'
        ]);

        try {
            $response = Http::timeout(30)->post("{$this->whatsappApiUrl}/{$sessionId}/send-template", [
                'number' => $request->number,
                'template' => $request->template,
                'templateData' => $request->templateData,
                'token' => $request->token
            ]);

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json([
                'success' => false,
                'error' => 'Failed to send template message',
                'session_id' => $sessionId,
                'status_code' => $response->status()
            ], $response->status());

        } catch (\Exception $e) {
            Log::error("WhatsApp API Error - sendTemplateMessage ({$sessionId}): " . $e->getMessage());

            return response()->json([
                'success' => false,
                'error' => 'Connection error to WhatsApp Gateway',
                'session_id' => $sessionId,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Logout session
     */
    public function logoutSession($sessionId)
    {
        try {
            $response = Http::timeout(30)->post("{$this->whatsappApiUrl}/{$sessionId}/logout");

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json([
                'success' => false,
                'error' => 'Failed to logout session',
                'session_id' => $sessionId,
                'status_code' => $response->status()
            ], $response->status());

        } catch (\Exception $e) {
            Log::error("WhatsApp API Error - logoutSession ({$sessionId}): " . $e->getMessage());

            return response()->json([
                'success' => false,
                'error' => 'Connection error to WhatsApp Gateway',
                'session_id' => $sessionId,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete session
     */
    public function deleteSession($sessionId)
    {
        try {
            $response = Http::timeout(30)->delete("{$this->whatsappApiUrl}/{$sessionId}/delete");

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json([
                'success' => false,
                'error' => 'Failed to delete session',
                'session_id' => $sessionId,
                'status_code' => $response->status()
            ], $response->status());

        } catch (\Exception $e) {
            Log::error("WhatsApp API Error - deleteSession ({$sessionId}): " . $e->getMessage());

            return response()->json([
                'success' => false,
                'error' => 'Connection error to WhatsApp Gateway',
                'session_id' => $sessionId,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Restart session
     */
    public function restartSession($sessionId)
    {
        try {
            $response = Http::timeout(30)->post("{$this->whatsappApiUrl}/{$sessionId}/restart");

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json([
                'success' => false,
                'error' => 'Failed to restart session',
                'session_id' => $sessionId,
                'status_code' => $response->status()
            ], $response->status());

        } catch (\Exception $e) {
            Log::error("WhatsApp API Error - restartSession ({$sessionId}): " . $e->getMessage());

            return response()->json([
                'success' => false,
                'error' => 'Connection error to WhatsApp Gateway',
                'session_id' => $sessionId,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Debug folders
     */
    public function debugFolders()
    {
        try {
            $response = Http::timeout(10)->get("{$this->whatsappApiUrl}/debug/folders");

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json([
                'success' => false,
                'error' => 'Failed to get debug info',
                'status_code' => $response->status()
            ], $response->status());

        } catch (\Exception $e) {
            Log::error('WhatsApp API Error - debugFolders: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'error' => 'Connection error to WhatsApp Gateway',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Refresh sessions
     */
    public function refreshSessions()
    {
        try {
            $response = Http::timeout(30)->post("{$this->whatsappApiUrl}/refresh-sessions");

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json([
                'success' => false,
                'error' => 'Failed to refresh sessions',
                'status_code' => $response->status()
            ], $response->status());

        } catch (\Exception $e) {
            Log::error('WhatsApp API Error - refreshSessions: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'error' => 'Connection error to WhatsApp Gateway',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test endpoint
     */
    public function testEndpoint(Request $request)
    {
        try {
            $response = Http::timeout(10)->post("{$this->whatsappApiUrl}/test", $request->all());

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json([
                'success' => false,
                'error' => 'Test failed',
                'status_code' => $response->status()
            ], $response->status());

        } catch (\Exception $e) {
            Log::error('WhatsApp API Error - testEndpoint: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'error' => 'Connection error to WhatsApp Gateway',
                'message' => $e->getMessage()
            ], 500);
        }
    }

  public function getPasienInfo(Request $request)
{
    $telepon = $request->query('telepon');

    $pasien = Pasien::where('telepon', $telepon)->first();

    if (!$pasien) {
        return response()->json(['error' => 'Pasien tidak ditemukan'], 404);
    }

    $antrian = $pasien->pasienAntrian;

    return response()->json([
        'nama' => $pasien->nama,
        'telepon' => $pasien->telepon,
        'no_antrian' => $antrian ? $antrian->nomor_antrian : 'Belum ada'
    ]);
}

}
