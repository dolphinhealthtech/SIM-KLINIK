<?php

namespace App\Http\Controllers;

use App\Models\Set_Sehat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;

class SatusehatController extends Controller
{
    public function get_token()
    {
        $config = Set_Sehat::find(1);

        if (!$config) {
            abort(500, 'Configuration not found.');
        }

        $response = Http::asForm()->withHeaders([
            'Content-Type' => 'application/x-www-form-urlencoded',
            'User-Agent' => 'PostmanRuntime/7.26.10',
        ])->post("{$config->SATUSEHAT_BASE_URL}/oauth2/v1/accesstoken?grant_type=client_credentials", [
            'client_id' => $config->client_id,
            'client_secret' => $config->client_secret,
        ]);

        return [
            'access_token'=> $response->json('access_token'),
        ];
    }

    public function get_nik_satusehat($nik)
    {
        $config = Set_Sehat::find(1);
        $BASE_URL = $config->SATUSEHAT_BASE_URL;
        $feature = 'fhir-r4/v1/Patient?identifier=';
        $maxRetries = 3;
        $data = null;
        $responseTime = 0;
        $url = urlencode('https://fhir.kemkes.go.id/id/nik|' . $nik);

        // Ambil token hanya sekali
        $token = $this->get_token();

        if (!$token) {
            return response()->json(['error' => 'Unable to obtain access token'], 500);
        }

        $headers = array_merge([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token['access_token']
        ]);

        for ($attempt = 0; $attempt < $maxRetries; $attempt++) {
            try {
                $startTime = microtime(true);

                // Kirim permintaan ke API
                $response = Http::withHeaders($headers)->get("{$BASE_URL}/{$feature}{$url}");

                $endTime = microtime(true);
                $responseTime = round(($endTime - $startTime) * 1000, 2); // dalam milidetik
                if ($response->successful()) {
                    $responseBody = $response->json();

                    if ($data !== null) {
                        break; // Keluar dari loop jika berhasil
                    }
                }
            } catch (\Exception $e) {
                if ($attempt >= $maxRetries - 1) {
                    return response()->json([
                        'status' => 'error',
                        'message' => $e->getMessage(),
                        'response_time_ms' => $responseTime
                    ], 400);
                }
            }
        }

        $patientId = isset($responseBody['entry'][0]['resource']['id'])
        ? $responseBody['entry'][0]['resource']['id']
        : null;
        return response()->json([
            "status" => "success",
            "data" => $patientId,
            "response_time_ms" => $responseTime
        ]);
    }
    public function get_nik_practitioner_satusehat($nik)
    {
        $config = Set_Sehat::find(1);
        $BASE_URL = $config->SATUSEHAT_BASE_URL;
        $feature = 'fhir-r4/v1/Practitioner?identifier=';
        $maxRetries = 3;
        $data = null;
        $responseTime = 0;
        $url = urlencode('https://fhir.kemkes.go.id/id/nik|' . $nik);

        // Ambil token hanya sekali
        $token = $this->get_token();

        if (!$token) {
            return response()->json(['error' => 'Unable to obtain access token'], 500);
        }

        $headers = array_merge([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token['access_token']
        ]);

        for ($attempt = 0; $attempt < $maxRetries; $attempt++) {
            try {
                $startTime = microtime(true);

                // Kirim permintaan ke API
                $response = Http::withHeaders($headers)->get("{$BASE_URL}/{$feature}{$url}");

                $endTime = microtime(true);
                $responseTime = round(($endTime - $startTime) * 1000, 2); // dalam milidetik
                if ($response->successful()) {
                    $responseBody = $response->json();

                    if ($data !== null) {
                        break; // Keluar dari loop jika berhasil
                    }
                }
            } catch (\Exception $e) {
                if ($attempt >= $maxRetries - 1) {
                    return response()->json([
                        'status' => 'error',
                        'message' => $e->getMessage(),
                        'response_time_ms' => $responseTime
                    ], 400);
                }
            }
        }

        return response()->json([
            "status" => "success",
            "data" => $responseBody,
            "response_time_ms" => $responseTime
        ]);
    }


    public function get_kfa_satusehat($nama)
    {

        $config = Set_Sehat::find(1);
        $BASE_URL = $config->SATUSEHAT_BASE_URL;
        $feature = '/kfa-v2/products/all?page=1&size=100&product_type=kfa&keyword=';
        $maxRetries = 3;
        $data = null;
        $responseTime = 0;


         // Ambil token hanya sekali
        $token = $this->get_token();

        if (!$token) {
            return response()->json(['error' => 'Unable to obtain access token'], 500);
        }

        $headers = array_merge([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token['access_token']
        ]);
        // Cek nama KFA
        if (empty($nama)) {
            return response()->json(['error' => 'Nama KFA tidak boleh kosong'], 400);
        }

        for ($attempt = 0; $attempt < $maxRetries; $attempt++) {
            try {
                $startTime = microtime(true);

                // Kirim permintaan ke API
                $response = Http::withHeaders($headers)->get("{$BASE_URL}/{$feature}{$nama}");

                $endTime = microtime(true);
                $responseTime = round(($endTime - $startTime) * 1000, 2); // dalam milidetik
                if ($response->successful()) {
                    $responseBody = $response->json();

                    // Filter data active == true dan ambil field yang dibutuhkan
                    $filteredData = collect($responseBody['items']['data'] ?? [])
                        ->where('active', true)
                        ->map(function ($item) {
                            return [
                                'name' => $item['name'] ?? '',
                                'kfa_code' => $item['kfa_code'] ?? '',
                                'manufacturer' => $item['manufacturer'] ?? '',
                            ];
                        })
                        ->values(); // Reset index array

                    $data = $filteredData;
                    break; // Keluar dari loop jika berhasil
                }

            } catch (\Exception $e) {
                if ($attempt >= $maxRetries - 1) {
                    return response()->json([
                        'status' => 'error',
                        'message' => $e->getMessage(),
                        'response_time_ms' => $responseTime
                    ], 400);
                }
            }
        }

        return response()->json([
            "status" => "success",
            "data" => $data,
            "response_time_ms" => $responseTime
        ]);

    }
}
