<?php

namespace App\Http\Controllers;

use App\Models\Set_Bpjs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use LZCompressor\LZString;
use Illuminate\Support\Facades\Log;

class PcareController extends Controller
{
    public function get_token()
    {
        $config = Set_Bpjs::find(1);

        $cons_id = $config->CONSID;
        $secret_key = $config->SCREET_KEY;
        $username = $config->USERNAME;
        $password = $config->PASSWORD;
        $app_code = $config->APP_CODE;
        $user_key = $config->USER_KEY;


        date_default_timezone_set('UTC');
        $timestamp = strval(time() - strtotime('1970-01-01 00:00:00'));


        $data = "{$cons_id}&{$timestamp}";
        $signature = hash_hmac('sha256', $data, $secret_key, true);
        $encodedSignature = base64_encode($signature);

        $key_decrypt = $cons_id . $secret_key . $timestamp;
        $signature = $encodedSignature;


        $data = "{$username}:{$password}:{$app_code}";
        $encodedAuth = base64_encode($data);
        $authorization = "Basic {$encodedAuth}";

        $data = [
            'X-cons-id'       => $cons_id,
            'X-Timestamp'     => $timestamp,
            'X-Signature'     => $signature,
            'X-Authorization' => $authorization,
            'user_key' => $user_key,
        ];

        return [
            'headers'    => $data,
            'key_decrypt' => $key_decrypt,
        ];
    }

    public function get_noka_bpjs($noka)
    {
        $config = set_bpjs::find(1);
        $BASE_URL = $config->BASE_URL;
        $SERVICE_NAME = $config->SERVICE;
        $feature = 'peserta';
        $maxRetries = 3;
        $attempt = 0;
        $data = null;
        $responseTime = 0;

        while ($attempt < $maxRetries && $data === null) {
            try {
                $startTime = microtime(true);

                $headers = array_merge([
                    'Content-Type' => 'application/json; charset=utf-8'
                ], $this->get_token()['headers']);
                $response = Http::withHeaders($headers)->get("{$BASE_URL}/{$SERVICE_NAME}/{$feature}/{$noka}");
                $responseBody = json_decode($response->body(), true);

                $endTime = microtime(true);
                $responseTime = $endTime - $startTime;

                // Fetch the encrypted response data
                $encryptedString = $responseBody['response'];


                // Decrypt the string using AES-256-CBC
                $key = $this->get_token()['key_decrypt'];
                $encrypt_method = 'AES-256-CBC';
                $key_hash = substr(hex2bin(hash('sha256', $key)), 0, 32);  // Get key hash
                $iv = substr(hex2bin(hash('sha256', $key)), 0, 16);  // Get IV

                // Log sebelum dekripsi
                Log::info("Mulai proses dekripsi", [
                    'encryptedString' => $encryptedString,
                    'key' => $key,
                    'key_hash' => bin2hex($key_hash),
                    'iv' => bin2hex($iv)
                ]);
                // Decrypt the base64-encoded encrypted string
                // $decryptedString = openssl_decrypt(base64_decode($encryptedString), $encrypt_method, $key_hash, OPENSSL_RAW_DATA, $iv);
                $decryptedString = openssl_decrypt(
                    base64_decode($encryptedString),
                    $encrypt_method,
                    $key_hash,
                    OPENSSL_RAW_DATA, // Bisa coba tambahkan | OPENSSL_ZERO_PADDING jika masih gagal
                    $iv
                );


                Log::info("Hasil dekripsi", [
                    'decryptedString' => $decryptedString
                ]);

                $jsonString = LZString::decompressFromEncodedURIComponent($decryptedString);

                // Jika gagal decompress, log error dan beri respons error
                if ($jsonString === false || $jsonString === null) {
                    Log::error("Gagal decompress", [
                        'decryptedString' => $decryptedString
                    ]);
                    return response()->json(['status' => 'error', 'message' => 'Decompress failed'], 400);
                }
                Log::info("Hasil decompressed", [
                    'jsonString' => $jsonString
                ]);

                // Decompress the string
                $data = json_decode($jsonString, true);

                if ($data !== null) {
                    break;
                }
            } catch (\Exception $e) {
                if ($attempt >= $maxRetries - 1) {
                    return response()->json(['status' => 'error', 'message' => $e->getMessage(), 'response_time' => $responseTime], 400);
                }
            }
            $attempt++;
        }

        return response()->json([
            "data" => $data,
            "response_time" => number_format($responseTime, 2)
        ]);
    }

    public function get_nik_bpjs($nik)
    {
        $config = set_bpjs::find(1);
        if (!$config) {
            return response()->json(['status' => 'error', 'message' => 'Config not found'], 500);
        }

        $BASE_URL = $config->BASE_URL;
        $SERVICE_NAME = $config->SERVICE;
        $feature = 'peserta/nik';
        $maxRetries = 3;
        $data = null;
        $responseTime = 0;

        // Ambil token hanya sekali
        $tokenData = $this->get_token();
        if (!$tokenData || !isset($tokenData['headers'], $tokenData['key_decrypt'])) {
            return response()->json(['status' => 'error', 'message' => 'Failed to retrieve token'], 500);
        }

        $headers = array_merge([
            'Content-Type' => 'application/json; charset=utf-8'
        ], $tokenData['headers']);

        for ($attempt = 0; $attempt < $maxRetries; $attempt++) {
            try {
                $startTime = microtime(true);

                // Kirim permintaan ke API
                $response = Http::withHeaders($headers)->get("{$BASE_URL}/{$SERVICE_NAME}/{$feature}/{$nik}");

                $endTime = microtime(true);
                $responseTime = round(($endTime - $startTime) * 1000, 2); // dalam milidetik

                // Jika respons berhasil, langsung proses dan keluar dari loop
                if ($response->successful()) {
                    $responseBody = $response->json();

                    if (!isset($responseBody['response'])) {
                        return response()->json(['status' => 'error', 'message' => 'Invalid response format'], 500);
                    }

                    // Dekripsi data
                    $encryptedString = $responseBody['response'];


                    // Decrypt the string using AES-256-CBC
                    $key = $this->get_token()['key_decrypt'];
                    $encrypt_method = 'AES-256-CBC';
                    $key_hash = substr(hex2bin(hash('sha256', $key)), 0, 32);  // Get key hash
                    $iv = substr(hex2bin(hash('sha256', $key)), 0, 16);  // Get IV

                    // Log sebelum dekripsi
                    Log::info("Mulai proses dekripsi", [
                        'encryptedString' => $encryptedString,
                        'key' => $key,
                        'key_hash' => bin2hex($key_hash),
                        'iv' => bin2hex($iv)
                    ]);
                    // Decrypt the base64-encoded encrypted string
                    // $decryptedString = openssl_decrypt(base64_decode($encryptedString), $encrypt_method, $key_hash, OPENSSL_RAW_DATA, $iv);
                    $decryptedString = openssl_decrypt(
                        base64_decode($encryptedString),
                        $encrypt_method,
                        $key_hash,
                        OPENSSL_RAW_DATA, // Bisa coba tambahkan | OPENSSL_ZERO_PADDING jika masih gagal
                        $iv
                    );


                    Log::info("Hasil dekripsi", [
                        'decryptedString' => $decryptedString
                    ]);

                    $jsonString = LZString::decompressFromEncodedURIComponent($decryptedString);

                    // Jika gagal decompress, log error dan beri respons error
                    if ($jsonString === false || $jsonString === null) {
                        Log::error("Gagal decompress", [
                            'decryptedString' => $decryptedString
                        ]);
                        return response()->json(['status' => 'error', 'message' => 'Decompress failed'], 400);
                    }
                    Log::info("Hasil decompressed", [
                        'jsonString' => $jsonString
                    ]);

                    $data = json_decode($jsonString, true);

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
            "data" => $data,
            "response_time_ms" => $responseTime
        ]);
    }

    public function get_poli_fktp_bpjs()
    {
        $config = set_bpjs::find(1);
        $BASE_URL = $config->BASE_URL;
        $SERVICE_NAME = $config->SERVICE;
        $feature = 'poli/fktp';
        $params = '0';
        $params1 = '500';
        $maxRetries = 3;
        $attempt = 0;
        $data = null;
        $responseTime = 0;

        while ($attempt < $maxRetries && $data === null) {
            try {
                $startTime = microtime(true);

                // Assuming $this->generateHeaders() returns an array of headers
                $headers = array_merge([
                    'Content-Type' => 'application/json; charset=utf-8'
                ], $this->get_token()['headers']);

                // Make the API request
                $response = Http::withHeaders($headers)
                    ->get("{$BASE_URL}/{$SERVICE_NAME}/{$feature}/{$params}/{$params1}");

                // Decode the response body
                $responseBody = json_decode($response->body(), true);

                $endTime = microtime(true);
                $responseTime = $endTime - $startTime;

                // Fetch the encrypted response data
                $encryptedString = $responseBody['response'];

                // Decrypt the string using AES-256-CBC
                $key = $this->get_token()['key_decrypt'];
                $encrypt_method = 'AES-256-CBC';
                $key_hash = substr(hex2bin(hash('sha256', $key)), 0, 32);  // Get key hash
                $iv = substr(hex2bin(hash('sha256', $key)), 0, 16);  // Get IV

                // Log sebelum dekripsi
                Log::info("Mulai proses dekripsi", [
                    'encryptedString' => $encryptedString,
                    'key' => $key,
                    'key_hash' => bin2hex($key_hash),
                    'iv' => bin2hex($iv)
                ]);
                // Decrypt the base64-encoded encrypted string
                // $decryptedString = openssl_decrypt(base64_decode($encryptedString), $encrypt_method, $key_hash, OPENSSL_RAW_DATA, $iv);
                $decryptedString = openssl_decrypt(
                    base64_decode($encryptedString),
                    $encrypt_method,
                    $key_hash,
                    OPENSSL_RAW_DATA, // Bisa coba tambahkan | OPENSSL_ZERO_PADDING jika masih gagal
                    $iv
                );


                Log::info("Hasil dekripsi", [
                    'decryptedString' => $decryptedString
                ]);

                $jsonString = LZString::decompressFromEncodedURIComponent($decryptedString);

                // Jika gagal decompress, log error dan beri respons error
                if ($jsonString === false || $jsonString === null) {
                    Log::error("Gagal decompress", [
                        'decryptedString' => $decryptedString
                    ]);
                    return response()->json(['status' => 'error', 'message' => 'Decompress failed'], 400);
                }
                Log::info("Hasil decompressed", [
                    'jsonString' => $jsonString
                ]);

                // Decompress the string
                $data = json_decode($jsonString, true);

                if ($data !== null) {
                    break;
                }
            } catch (\Exception $e) {
                if ($attempt >= $maxRetries - 1) {
                    return response()->json(['status' => 'error', 'message' => $e->getMessage(), 'response_time' => number_format($responseTime, 2)], 400);
                }
            }
            $attempt++;
        }

        // Check if data is null or empty
        if (empty($data) || !isset($data['list']) || empty($data['list'])) {
            return response()->json(['status' => 'error', 'message' => 'No data found', 'response_time' => number_format($responseTime, 2)], 400);
        }

        // Transform the data
        $transformedData = array_map(function ($practitioner) {
            return [
                'kode_poli' => $practitioner['kdPoli'],
                'nama_poli' => $practitioner['nmPoli'],
                'jenis_poli' => $practitioner['poliSakit'] ? 'pengobatan penyakit' : 'pelayanan kesehatan'
            ];
        }, $data['list']);

        return response()->json([
            'data' => $transformedData,
            'response_time' => number_format($responseTime, 2)
        ]);
    }

    public function get_dokter_bpjs()
    {
        $config = set_bpjs::find(1);
        $BASE_URL = $config->BASE_URL;
        $SERVICE_NAME = $config->SERVICE;
        $feature = 'dokter';
        $params = '1';
        $params1 = '100';
        $maxRetries = 3;
        $attempt = 0;
        $data = null;
        $responseTime = 0;

        while ($attempt < $maxRetries && $data === null) {
            try {
                $startTime = microtime(true);

                // Assuming $this->generateHeaders() returns an array of headers
                $headers = array_merge([
                    'Content-Type' => 'application/json; charset=utf-8'
                ], $this->get_token()['headers']);

                // Make the API request
                $response = Http::withHeaders($headers)
                    ->get("{$BASE_URL}/{$SERVICE_NAME}/{$feature}/{$params}/{$params1}");

                // Decode the response body
                $responseBody = json_decode($response->body(), true);

                $endTime = microtime(true);
                $responseTime = $endTime - $startTime;

                // Fetch the encrypted response data
                $encryptedString = $responseBody['response'];

                // Decrypt the string using AES-256-CBC
                $key = $this->get_token()['key_decrypt'];
                $encrypt_method = 'AES-256-CBC';
                $key_hash = substr(hex2bin(hash('sha256', $key)), 0, 32);  // Get key hash
                $iv = substr(hex2bin(hash('sha256', $key)), 0, 16);  // Get IV

                // Log sebelum dekripsi
                Log::info("Mulai proses dekripsi", [
                    'encryptedString' => $encryptedString,
                    'key' => $key,
                    'key_hash' => bin2hex($key_hash),
                    'iv' => bin2hex($iv)
                ]);
                // Decrypt the base64-encoded encrypted string
                // $decryptedString = openssl_decrypt(base64_decode($encryptedString), $encrypt_method, $key_hash, OPENSSL_RAW_DATA, $iv);
                $decryptedString = openssl_decrypt(
                    base64_decode($encryptedString),
                    $encrypt_method,
                    $key_hash,
                    OPENSSL_RAW_DATA, // Bisa coba tambahkan | OPENSSL_ZERO_PADDING jika masih gagal
                    $iv
                );


                Log::info("Hasil dekripsi", [
                    'decryptedString' => $decryptedString
                ]);

                $jsonString = LZString::decompressFromEncodedURIComponent($decryptedString);

                // Jika gagal decompress, log error dan beri respons error
                if ($jsonString === false || $jsonString === null) {
                    Log::error("Gagal decompress", [
                        'decryptedString' => $decryptedString
                    ]);
                    return response()->json(['status' => 'error', 'message' => 'Decompress failed'], 400);
                }
                Log::info("Hasil decompressed", [
                    'jsonString' => $jsonString
                ]);
                // Decompress the string
                $data = json_decode($jsonString, true);

                if ($data !== null) {
                    break;
                }
            } catch (\Exception $e) {
                if ($attempt >= $maxRetries - 1) {
                    return response()->json(['status' => 'error', 'message' => $e->getMessage(), 'response_time' => number_format($responseTime, 2)], 400);
                }
            }
            $attempt++;
        }

        // Check if data is null or empty
        if (empty($data) || !isset($data['list']) || empty($data['list'])) {
            return response()->json(['status' => 'error', 'message' => 'No data found', 'response_time' => number_format($responseTime, 2)], 400);
        }
        return response()->json([
            "data" => $data,
            "response_time" => number_format($responseTime, 2)
        ]);
    }

    public function get_spesialis_bpjs()
    {
        $config = set_bpjs::find(1);
        $BASE_URL = $config->BASE_URL;
        $SERVICE_NAME = $config->SERVICE;
        $feature = 'spesialis';
        $maxRetries = 3;
        $attempt = 0;
        $data = null;
        $responseTime = 0;

        while ($attempt < $maxRetries && $data === null) {
            try {
                $startTime = microtime(true);

                // Assuming $this->generateHeaders() returns an array of headers
                $headers = array_merge([
                    'Content-Type' => 'application/json; charset=utf-8'
                ], $this->get_token()['headers']);

                // Make the API request
                $response = Http::withHeaders($headers)
                    ->get("{$BASE_URL}/{$SERVICE_NAME}/{$feature}");

                // Decode the response body
                $responseBody = json_decode($response->body(), true);

                $endTime = microtime(true);
                $responseTime = $endTime - $startTime;

                // Fetch the encrypted response data
                $encryptedString = $responseBody['response'];

                // Decrypt the string using AES-256-CBC
                $key = $this->get_token()['key_decrypt'];
                $encrypt_method = 'AES-256-CBC';
                $key_hash = substr(hex2bin(hash('sha256', $key)), 0, 32);  // Get key hash
                $iv = substr(hex2bin(hash('sha256', $key)), 0, 16);  // Get IV

                // Log sebelum dekripsi
                Log::info("Mulai proses dekripsi", [
                    'encryptedString' => $encryptedString,
                    'key' => $key,
                    'key_hash' => bin2hex($key_hash),
                    'iv' => bin2hex($iv)
                ]);

                // Decrypt the base64-encoded encrypted string
                $decryptedString = openssl_decrypt(
                    base64_decode($encryptedString),
                    $encrypt_method,
                    $key_hash,
                    OPENSSL_RAW_DATA, // Bisa coba tambahkan | OPENSSL_ZERO_PADDING jika masih gagal
                    $iv
                );

                Log::info("Hasil dekripsi", [
                    'decryptedString' => $decryptedString
                ]);

                $jsonString = LZString::decompressFromEncodedURIComponent($decryptedString);

                // Jika gagal decompress, log error dan beri respons error
                if ($jsonString === false || $jsonString === null) {
                    Log::error("Gagal decompress", [
                        'decryptedString' => $decryptedString
                    ]);
                    return response()->json(['status' => 'error', 'message' => 'Decompress failed'], 400);
                }
                Log::info("Hasil decompressed", [
                    'jsonString' => $jsonString
                ]);

                // Decompress the string
                $data = json_decode($jsonString, true);

                if ($data !== null) {
                    break;
                }
            } catch (\Exception $e) {
                if ($attempt >= $maxRetries - 1) {
                    return response()->json(['status' => 'error', 'message' => $e->getMessage(), 'response_time' => number_format($responseTime, 2)], 400);
                }
            }
            $attempt++;
        }

        // Check if data is null or empty
        if (empty($data) || !isset($data['list']) || empty($data['list'])) {
            return response()->json(['status' => 'error', 'message' => 'No data found', 'response_time' => number_format($responseTime, 2)], 400);
        }
        return response()->json([
            "data" => $data,
            "response_time" => number_format($responseTime, 2)
        ]);
    }

    public function get_sub_spesialis_bpjs($nama)
    {
        $config = set_bpjs::find(1);
        $BASE_URL = $config->BASE_URL;
        $SERVICE_NAME = $config->SERVICE;
        $feature = 'spesialis';
        $params = $nama;
        $params1 = 'subspesialis';
        $maxRetries = 3;
        $attempt = 0;
        $data = null;
        $responseTime = 0;

        while ($attempt < $maxRetries && $data === null) {
            try {
                $startTime = microtime(true);
                // Assuming $this->generateHeaders() returns an array of headers
                $headers = array_merge([
                    'Content-Type' => 'application/json; charset=utf-8'
                ], $this->get_token()['headers']);

                // Make the API request
                $response = Http::withHeaders($headers)
                    ->get("{$BASE_URL}/{$SERVICE_NAME}/{$feature}/{$params}/{$params1}");

                // Decode the response body
                $responseBody = json_decode($response->body(), true);

                $endTime = microtime(true);
                $responseTime = $endTime - $startTime;

                // Fetch the encrypted response data
                $encryptedString = $responseBody['response'];

                // Decrypt the string using AES-256-CBC
                $key = $this->get_token()['key_decrypt'];
                $encrypt_method = 'AES-256-CBC';
                $key_hash = substr(hex2bin(hash('sha256', $key)), 0, 32);  // Get key hash
                $iv = substr(hex2bin(hash('sha256', $key)), 0, 16);  // Get IV

                // Log sebelum dekripsi
                Log::info("Mulai proses dekripsi", [
                    'encryptedString' => $encryptedString,
                    'key' => $key,
                    'key_hash' => bin2hex($key_hash),
                    'iv' => bin2hex($iv)
                ]);

                // Decrypt the base64-encoded encrypted string
                $decryptedString = openssl_decrypt(
                    base64_decode($encryptedString),
                    $encrypt_method,
                    $key_hash,
                    OPENSSL_RAW_DATA, // Bisa coba tambahkan | OPENSSL_ZERO_PADDING jika masih gagal
                    $iv
                );

                Log::info("Hasil dekripsi", [
                    'decryptedString' => $decryptedString
                ]);

                $jsonString = LZString::decompressFromEncodedURIComponent($decryptedString);

                // Jika gagal decompress, log error dan beri respons error
                if ($jsonString === false || $jsonString === null) {
                    Log::error("Gagal decompress", [
                        'decryptedString' => $decryptedString
                    ]);
                    return response()->json(['status' => 'error', 'message' => 'Decompress failed'], 400);
                }
                Log::info("Hasil decompressed", [
                    'jsonString' => $jsonString
                ]);

                // Decompress the string
                $data = json_decode($jsonString, true);

                if ($data !== null) {
                    break;
                }
            } catch (\Exception $e) {
                if ($attempt >= $maxRetries - 1) {
                    return response()->json(['status' => 'error', 'message' => $e->getMessage(), 'response_time' => number_format($responseTime, 2)], 400);
                }
            }
            $attempt++;
        }

        // Check if data is null or empty
        if (empty($data) || !isset($data['list']) || empty($data['list'])) {
            return response()->json(['status' => 'error', 'message' => 'No data found', 'response_time' => number_format($responseTime, 2)], 400);
        }
        return response()->json([
            "data" => $data,
            "response_time" => number_format($responseTime, 2)
        ]);
    }

    public function get_diagnosis_bpjs($nama)
    {
        $config = set_bpjs::find(1);
        $BASE_URL = $config->BASE_URL;
        $SERVICE_NAME =  $config->SERVICE;
        $feature = 'diagnosa';
        $params = $nama;
        $params1 = '0';
        $params2 = '500';
        $maxRetries = 3;
        $attempt = 0;
        $data = null;
        $responseTime = 0;

        while ($attempt < $maxRetries && $data === null) {
            try {
                $startTime = microtime(true);
                // Assuming $this->generateHeaders() returns an array of headers
                $headers = array_merge([
                    'Content-Type' => 'application/json; charset=utf-8'
                ], $this->get_token()['headers']);

                // Make the API request
                $response = Http::withHeaders($headers)
                    ->get("{$BASE_URL}/{$SERVICE_NAME}/{$feature}/{$params}/{$params1}/{$params2}");

                // Decode the response body
                $responseBody = json_decode($response->body(), true);

                $endTime = microtime(true);
                $responseTime = $endTime - $startTime;

                // Fetch the encrypted response data
                $encryptedString = $responseBody['response'];

                // Decrypt the string using AES-256-CBC
                $key = $this->get_token()['key_decrypt'];
                $encrypt_method = 'AES-256-CBC';
                $key_hash = substr(hex2bin(hash('sha256', $key)), 0, 32);  // Get key hash
                $iv = substr(hex2bin(hash('sha256', $key)), 0, 16);  // Get IV

                // Log sebelum dekripsi
                Log::info("Mulai proses dekripsi", [
                    'encryptedString' => $encryptedString,
                    'key' => $key,
                    'key_hash' => bin2hex($key_hash),
                    'iv' => bin2hex($iv)
                ]);

                // Decrypt the base64-encoded encrypted string
                $decryptedString = openssl_decrypt(
                    base64_decode($encryptedString),
                    $encrypt_method,
                    $key_hash,
                    OPENSSL_RAW_DATA, // Bisa coba tambahkan | OPENSSL_ZERO_PADDING jika masih gagal
                    $iv
                );

                Log::info("Hasil dekripsi", [
                    'decryptedString' => $decryptedString
                ]);

                $jsonString = LZString::decompressFromEncodedURIComponent($decryptedString);

                // Jika gagal decompress, log error dan beri respons error
                if ($jsonString === false || $jsonString === null) {
                    Log::error("Gagal decompress", [
                        'decryptedString' => $decryptedString
                    ]);
                    return response()->json(['status' => 'error', 'message' => 'Decompress failed'], 400);
                }
                Log::info("Hasil decompressed", [
                    'jsonString' => $jsonString
                ]);

                // Decompress the string
                $data = json_decode($jsonString, true);

                if ($data !== null) {
                    break;
                }
            } catch (\Exception $e) {
                if ($attempt >= $maxRetries - 1) {
                    return response()->json(['status' => 'error', 'message' => $e->getMessage(), 'response_time' => number_format($responseTime, 2)], 400);
                }
            }
            $attempt++;
        }

        // Check if data is null or empty
        if (empty($data) || !isset($data['list']) || empty($data['list'])) {
            return response()->json(['status' => 'error', 'message' => 'No data found', 'response_time' => number_format($responseTime, 2)], 400);
        }
        return response()->json([
            "data" => $data,
            "response_time" => number_format($responseTime, 2)
        ]);
    }

    public function get_statpul_bpjs($nama)
    {
        $config = set_bpjs::find(1);
        $BASE_URL = $config->BASE_URL;
        $SERVICE_NAME = $config->SERVICE;
        $feature = 'statuspulang/rawatInap';
        $params = $nama;
        $maxRetries = 3;
        $attempt = 0;
        $data = null;
        $responseTime = 0;

        while ($attempt < $maxRetries && $data === null) {
            try {
                $startTime = microtime(true);
                // Assuming $this->generateHeaders() returns an array of headers
                $headers = array_merge([
                    'Content-Type' => 'application/json; charset=utf-8'
                ], $this->get_token()['headers']);

                // Make the API request
                $response = Http::withHeaders($headers)
                    ->get("{$BASE_URL}/{$SERVICE_NAME}/{$feature}/{$params}");
                // Decode the response body
                $responseBody = json_decode($response->body(), true);

                $endTime = microtime(true);
                $responseTime = $endTime - $startTime;

                // Fetch the encrypted response data
                $encryptedString = $responseBody['response'];

                // Decrypt the string using AES-256-CBC
                $key = $this->get_token()['key_decrypt'];
                $encrypt_method = 'AES-256-CBC';
                $key_hash = substr(hex2bin(hash('sha256', $key)), 0, 32);  // Get key hash
                $iv = substr(hex2bin(hash('sha256', $key)), 0, 16);  // Get IV

                // Log sebelum dekripsi
                Log::info("Mulai proses dekripsi", [
                    'encryptedString' => $encryptedString,
                    'key' => $key,
                    'key_hash' => bin2hex($key_hash),
                    'iv' => bin2hex($iv)
                ]);

                // Decrypt the base64-encoded encrypted string
                $decryptedString = openssl_decrypt(
                    base64_decode($encryptedString),
                    $encrypt_method,
                    $key_hash,
                    OPENSSL_RAW_DATA, // Bisa coba tambahkan | OPENSSL_ZERO_PADDING jika masih gagal
                    $iv
                );

                Log::info("Hasil dekripsi", [
                    'decryptedString' => $decryptedString
                ]);

                $jsonString = LZString::decompressFromEncodedURIComponent($decryptedString);

                // Jika gagal decompress, log error dan beri respons error
                if ($jsonString === false || $jsonString === null) {
                    Log::error("Gagal decompress", [
                        'decryptedString' => $decryptedString
                    ]);
                    return response()->json(['status' => 'error', 'message' => 'Decompress failed'], 400);
                }
                Log::info("Hasil decompressed", [
                    'jsonString' => $jsonString
                ]);

                // Decompress the string
                $data = json_decode($jsonString, true);

                if ($data !== null) {
                    break;
                }
            } catch (\Exception $e) {
                if ($attempt >= $maxRetries - 1) {
                    return response()->json(['status' => 'error', 'message' => $e->getMessage(), 'response_time' => number_format($responseTime, 2)], 400);
                }
            }
            $attempt++;
        }

        // Check if data is null or empty
        if (empty($data) || !isset($data['list']) || empty($data['list'])) {
            return response()->json(['status' => 'error', 'message' => 'No data found', 'response_time' => number_format($responseTime, 2)], 400);
        }
        return response()->json([
            "data" => $data,
            "response_time" => number_format($responseTime, 2)
        ]);
    }

    public function get_kesadaran_bpjs()
    {
        $config = set_bpjs::find(1);
        $BASE_URL = $config->BASE_URL;
        $SERVICE_NAME = $config->SERVICE;
        $feature = 'kesadaran';
        $maxRetries = 3;
        $attempt = 0;
        $data = null;
        $responseTime = 0;

        while ($attempt < $maxRetries && $data === null) {
            try {
                $startTime = microtime(true);
                // Assuming $this->generateHeaders() returns an array of headers
                $headers = array_merge([
                    'Content-Type' => 'application/json; charset=utf-8'
                ], $this->get_token()['headers']);

                // Make the API request
                $response = Http::withHeaders($headers)
                    ->get("{$BASE_URL}/{$SERVICE_NAME}/{$feature}");
                // Decode the response body
                $responseBody = json_decode($response->body(), true);

                $endTime = microtime(true);
                $responseTime = $endTime - $startTime;

                // Fetch the encrypted response data
                $encryptedString = $responseBody['response'];

                // Decrypt the string using AES-256-CBC
                $key = $this->get_token()['key_decrypt'];
                $encrypt_method = 'AES-256-CBC';
                $key_hash = substr(hex2bin(hash('sha256', $key)), 0, 32);  // Get key hash
                $iv = substr(hex2bin(hash('sha256', $key)), 0, 16);  // Get IV

                // Log sebelum dekripsi
                Log::info("Mulai proses dekripsi", [
                    'encryptedString' => $encryptedString,
                    'key' => $key,
                    'key_hash' => bin2hex($key_hash),
                    'iv' => bin2hex($iv)
                ]);

                // Decrypt the base64-encoded encrypted string
                $decryptedString = openssl_decrypt(
                    base64_decode($encryptedString),
                    $encrypt_method,
                    $key_hash,
                    OPENSSL_RAW_DATA, // Bisa coba tambahkan | OPENSSL_ZERO_PADDING jika masih gagal
                    $iv
                );

                Log::info("Hasil dekripsi", [
                    'decryptedString' => $decryptedString
                ]);

                $jsonString = LZString::decompressFromEncodedURIComponent($decryptedString);

                // Jika gagal decompress, log error dan beri respons error
                if ($jsonString === false || $jsonString === null) {
                    Log::error("Gagal decompress", [
                        'decryptedString' => $decryptedString
                    ]);
                    return response()->json(['status' => 'error', 'message' => 'Decompress failed'], 400);
                }
                Log::info("Hasil decompressed", [
                    'jsonString' => $jsonString
                ]);

                // Decompress the string
                $data = json_decode($jsonString, true);

                if ($data !== null) {
                    break;
                }
            } catch (\Exception $e) {
                if ($attempt >= $maxRetries - 1) {
                    return response()->json(['status' => 'error', 'message' => $e->getMessage(), 'response_time' => number_format($responseTime, 2)], 400);
                }
            }
            $attempt++;
        }

        // Check if data is null or empty
        if (empty($data) || !isset($data['list']) || empty($data['list'])) {
            return response()->json(['status' => 'error', 'message' => 'No data found', 'response_time' => number_format($responseTime, 2)], 400);
        }
        return response()->json([
            "data" => $data,
            "response_time" => number_format($responseTime, 2)
        ]);
    }

    public function get_provider_bpjs()
    {
        $config = set_bpjs::find(1);
        $BASE_URL = $config->BASE_URL;
        $SERVICE_NAME = $config->SERVICE;
        $feature = 'provider';
        $params1 = '0';
        $params2 = '50';
        $maxRetries = 3;
        $attempt = 0;
        $data = null;
        $responseTime = 0;

        while ($attempt < $maxRetries && $data === null) {
            try {
                $startTime = microtime(true);
                // Assuming $this->generateHeaders() returns an array of headers
                $headers = array_merge([
                    'Content-Type' => 'application/json; charset=utf-8'
                ], $this->get_token()['headers']);

                // Make the API request
                $response = Http::withHeaders($headers)
                    ->get("{$BASE_URL}/{$SERVICE_NAME}/{$feature}/{$params1}/{$params2}");
                // Decode the response body
                $responseBody = json_decode($response->body(), true);

                $endTime = microtime(true);
                $responseTime = $endTime - $startTime;

                // Fetch the encrypted response data
                $encryptedString = $responseBody['response'];

                // Decrypt the string using AES-256-CBC
                $key = $this->get_token()['key_decrypt'];
                $encrypt_method = 'AES-256-CBC';
                $key_hash = substr(hex2bin(hash('sha256', $key)), 0, 32);  // Get key hash
                $iv = substr(hex2bin(hash('sha256', $key)), 0, 16);  // Get IV

                // Log sebelum dekripsi
                Log::info("Mulai proses dekripsi", [
                    'encryptedString' => $encryptedString,
                    'key' => $key,
                    'key_hash' => bin2hex($key_hash),
                    'iv' => bin2hex($iv)
                ]);

                // Decrypt the base64-encoded encrypted string
                $decryptedString = openssl_decrypt(
                    base64_decode($encryptedString),
                    $encrypt_method,
                    $key_hash,
                    OPENSSL_RAW_DATA, // Bisa coba tambahkan | OPENSSL_ZERO_PADDING jika masih gagal
                    $iv
                );

                Log::info("Hasil dekripsi", [
                    'decryptedString' => $decryptedString
                ]);

                $jsonString = LZString::decompressFromEncodedURIComponent($decryptedString);

                // Jika gagal decompress, log error dan beri respons error
                if ($jsonString === false || $jsonString === null) {
                    Log::error("Gagal decompress", [
                        'decryptedString' => $decryptedString
                    ]);
                    return response()->json(['status' => 'error', 'message' => 'Decompress failed'], 400);
                }
                Log::info("Hasil decompressed", [
                    'jsonString' => $jsonString
                ]);

                // Decompress the string
                $data = json_decode($jsonString, true);

                if ($data !== null) {
                    break;
                }
            } catch (\Exception $e) {
                if ($attempt >= $maxRetries - 1) {
                    return response()->json(['status' => 'error', 'message' => $e->getMessage(), 'response_time' => number_format($responseTime, 2)], 400);
                }
            }
            $attempt++;
        }

        // Check if data is null or empty
        if (empty($data) || !isset($data['list']) || empty($data['list'])) {
            return response()->json(['status' => 'error', 'message' => 'No data found', 'response_time' => number_format($responseTime, 2)], 400);
        }
        return response()->json([
            "data" => $data,
            "response_time" => number_format($responseTime, 2)
        ]);
    }

    public function get_khusus_bpjs()
    {
        $config = set_bpjs::find(1);
        $BASE_URL = $config->BASE_URL;
        $SERVICE_NAME = $config->SERVICE;
        $feature = 'spesialis/khusus';
        $maxRetries = 3;
        $attempt = 0;
        $data = null;
        $responseTime = 0;

        while ($attempt < $maxRetries && $data === null) {
            try {
                $startTime = microtime(true);
                // Assuming $this->generateHeaders() returns an array of headers
                $headers = array_merge([
                    'Content-Type' => 'application/json; charset=utf-8'
                ], $this->get_token()['headers']);

                $response = Http::withHeaders($headers)
                    ->get("{$BASE_URL}/{$SERVICE_NAME}/{$feature}");

                // Decode the response body
                $responseBody = json_decode($response->body(), true);

                $endTime = microtime(true);
                $responseTime = $endTime - $startTime;

                // Fetch the encrypted response data
                $encryptedString = $responseBody['response'];

                // Decrypt the string using AES-256-CBC
                $key = $this->get_token()['key_decrypt'];
                $encrypt_method = 'AES-256-CBC';
                $key_hash = substr(hex2bin(hash('sha256', $key)), 0, 32);  // Get key hash
                $iv = substr(hex2bin(hash('sha256', $key)), 0, 16);  // Get IV

                // Log sebelum dekripsi
                Log::info("Mulai proses dekripsi", [
                    'encryptedString' => $encryptedString,
                    'key' => $key,
                    'key_hash' => bin2hex($key_hash),
                    'iv' => bin2hex($iv)
                ]);

                // Decrypt the base64-encoded encrypted string
                $decryptedString = openssl_decrypt(
                    base64_decode($encryptedString),
                    $encrypt_method,
                    $key_hash,
                    OPENSSL_RAW_DATA, // Bisa coba tambahkan | OPENSSL_ZERO_PADDING jika masih gagal
                    $iv
                );

                Log::info("Hasil dekripsi", [
                    'decryptedString' => $decryptedString
                ]);

                $jsonString = LZString::decompressFromEncodedURIComponent($decryptedString);

                // Jika gagal decompress, log error dan beri respons error
                if ($jsonString === false || $jsonString === null) {
                    Log::error("Gagal decompress", [
                        'decryptedString' => $decryptedString
                    ]);
                    return response()->json(['status' => 'error', 'message' => 'Decompress failed'], 400);
                }
                Log::info("Hasil decompressed", [
                    'jsonString' => $jsonString
                ]);

                // Decompress the string
                $data = json_decode($jsonString, true);

                if ($data !== null) {
                    break;
                }
            } catch (\Exception $e) {
                if ($attempt >= $maxRetries - 1) {
                    return response()->json(['status' => 'error', 'message' => $e->getMessage(), 'response_time' => number_format($responseTime, 2)], 400);
                }
            }
            $attempt++;
        }

        // Check if data is null or empty
        if (empty($data) || !isset($data['list']) || empty($data['list'])) {
            return response()->json(['status' => 'error', 'message' => 'No data found', 'response_time' => number_format($responseTime, 2)], 400);
        }
        return response()->json([
            "data" => $data,
            "response_time" => number_format($responseTime, 2)
        ]);
    }

    public function get_dphoobat_bpjs($nama)
    {
        $config = set_bpjs::find(1);
        $BASE_URL = $config->BASE_URL;
        $SERVICE_NAME = $config->SERVICE;
        $feature = 'obat/dpho';
        $feature1 = '1';
        $feature2 = '50';
        $maxRetries = 3;
        $attempt = 0;
        $data = null;
        $responseTime = 0;

        while ($attempt < $maxRetries && $data === null) {
            try {
                $startTime = microtime(true);
                // Assuming $this->generateHeaders() returns an array of headers
                $headers = array_merge([
                    'Content-Type' => 'application/json; charset=utf-8'
                ], $this->get_token()['headers']);

                $response = Http::withHeaders($headers)
                    ->get("{$BASE_URL}/{$SERVICE_NAME}/{$feature}/{$nama}/{$feature1}/{$feature2}");

                // Decode response
                $responseBody = json_decode($response->body(), true);
                $endTime = microtime(true);
                $responseTime = $endTime - $startTime;

                // Fetch the encrypted response data
                $encryptedString = $responseBody['response'];

                // Decrypt the string using AES-256-CBC
                $key = $this->get_token()['key_decrypt'];
                $encrypt_method = 'AES-256-CBC';
                $key_hash = substr(hex2bin(hash('sha256', $key)), 0, 32);  // Get key hash
                $iv = substr(hex2bin(hash('sha256', $key)), 0, 16);  // Get IV

                // Log sebelum dekripsi
                Log::info("Mulai proses dekripsi", [
                    'encryptedString' => $encryptedString,
                    'key' => $key,
                    'key_hash' => bin2hex($key_hash),
                    'iv' => bin2hex($iv)
                ]);

                // Decrypt the base64-encoded encrypted string
                $decryptedString = openssl_decrypt(
                    base64_decode($encryptedString),
                    $encrypt_method,
                    $key_hash,
                    OPENSSL_RAW_DATA, // Bisa coba tambahkan | OPENSSL_ZERO_PADDING jika masih gagal
                    $iv
                );

                Log::info("Hasil dekripsi", [
                    'decryptedString' => $decryptedString
                ]);

                $jsonString = LZString::decompressFromEncodedURIComponent($decryptedString);

                // Jika gagal decompress, log error dan beri respons error
                if ($jsonString === false || $jsonString === null) {
                    Log::error("Gagal decompress", [
                        'decryptedString' => $decryptedString
                    ]);
                    return response()->json(['status' => 'error', 'message' => 'Decompress failed'], 400);
                }
                Log::info("Hasil decompressed", [
                    'jsonString' => $jsonString
                ]);

                // Decompress the string
                $data = json_decode($jsonString, true);

                if ($data !== null) {
                    break;
                }
            } catch (\Exception $e) {
                if ($attempt >= $maxRetries - 1) {
                    return response()->json(['status' => 'error', 'message' => $e->getMessage(), 'response_time' => number_format($responseTime, 2)], 400);
                }
            }
            $attempt++;
        }

        // Check if data is null or empty
        if (empty($data) || !isset($data['list']) || empty($data['list'])) {
            return response()->json(['status' => 'error', 'message' => 'No data found', 'response_time' => number_format($responseTime, 2)], 400);
        }
        return response()->json([
            "data" => $data,
            "response_time" => number_format($responseTime, 2)
        ]);
    }
    public function get_prognosa_bpjs()
    {
        $config = set_bpjs::find(1);
        $BASE_URL = $config->BASE_URL;
        $SERVICE_NAME = $config->SERVICE;
        $feature = 'prognosa';
        $maxRetries = 3;
        $attempt = 0;
        $data = null;
        $responseTime = 0;

        while ($attempt < $maxRetries && $data === null) {
            try {
                $startTime = microtime(true);
                // Assuming $this->generateHeaders() returns an array of headers
                $headers = array_merge([
                    'Content-Type' => 'application/json; charset=utf-8'
                ], $this->get_token()['headers']);

                $response = Http::withHeaders($headers)
                    ->get("{$BASE_URL}/{$SERVICE_NAME}/{$feature}");

                // Decode response
                $responseBody = json_decode($response->body(), true);
                $endTime = microtime(true);
                $responseTime = $endTime - $startTime;

                // Fetch the encrypted response data
                $encryptedString = $responseBody['response'];

                // Decrypt the string using AES-256-CBC
                $key = $this->get_token()['key_decrypt'];
                $encrypt_method = 'AES-256-CBC';
                $key_hash = substr(hex2bin(hash('sha256', $key)), 0, 32);  // Get key hash
                $iv = substr(hex2bin(hash('sha256', $key)), 0, 16);  // Get IV

                // Log sebelum dekripsi
                Log::info("Mulai proses dekripsi", [
                    'encryptedString' => $encryptedString,
                    'key' => $key,
                    'key_hash' => bin2hex($key_hash),
                    'iv' => bin2hex($iv)
                ]);

                // Decrypt the base64-encoded encrypted string
                $decryptedString = openssl_decrypt(
                    base64_decode($encryptedString),
                    $encrypt_method,
                    $key_hash,
                    OPENSSL_RAW_DATA, // Bisa coba tambahkan | OPENSSL_ZERO_PADDING jika masih gagal
                    $iv
                );

                Log::info("Hasil dekripsi", [
                    'decryptedString' => $decryptedString
                ]);

                $jsonString = LZString::decompressFromEncodedURIComponent($decryptedString);

                // Jika gagal decompress, log error dan beri respons error
                if ($jsonString === false || $jsonString === null) {
                    Log::error("Gagal decompress", [
                        'decryptedString' => $decryptedString
                    ]);
                    return response()->json(['status' => 'error', 'message' => 'Decompress failed'], 400);
                }
                Log::info("Hasil decompressed", [
                    'jsonString' => $jsonString
                ]);

                // Decompress the string
                $data = json_decode($jsonString, true);

                if ($data !== null) {
                    break;
                }
            } catch (\Exception $e) {
                if ($attempt >= $maxRetries - 1) {
                    return response()->json(['status' => 'error', 'message' => $e->getMessage(), 'response_time' => number_format($responseTime, 2)], 400);
                }
            }
            $attempt++;
        }

        // Check if data is null or empty
        if (empty($data) || !isset($data['list']) || empty($data['list'])) {
            return response()->json(['status' => 'error', 'message' => 'No data found', 'response_time' => number_format($responseTime, 2)], 400);
        }
        return response()->json([
            "data" => $data,
            "response_time" => number_format($responseTime, 2)
        ]);
    }
    public function get_alergi_bpjs($kode)
    {
        $config = set_bpjs::find(1);
        $BASE_URL = $config->BASE_URL;
        $SERVICE_NAME = $config->SERVICE;
        $feature = 'alergi/jenis';
        $maxRetries = 3;
        $attempt = 0;
        $data = null;
        $responseTime = 0;

        while ($attempt < $maxRetries && $data === null) {
            try {
                $startTime = microtime(true);
                // Assuming $this->generateHeaders() returns an array of headers
                $headers = array_merge([
                    'Content-Type' => 'application/json; charset=utf-8'
                ], $this->get_token()['headers']);

                $response = Http::withHeaders($headers)
                    ->get("{$BASE_URL}/{$SERVICE_NAME}/{$feature}/{$kode}");

                // Decode response
                $responseBody = json_decode($response->body(), true);
                $endTime = microtime(true);
                $responseTime = $endTime - $startTime;

                // Fetch the encrypted response data
                $encryptedString = $responseBody['response'];

                // Decrypt the string using AES-256-CBC
                $key = $this->get_token()['key_decrypt'];
                $encrypt_method = 'AES-256-CBC';
                $key_hash = substr(hex2bin(hash('sha256', $key)), 0, 32);  // Get key hash
                $iv = substr(hex2bin(hash('sha256', $key)), 0, 16);  // Get IV

                // Log sebelum dekripsi
                Log::info("Mulai proses dekripsi", [
                    'encryptedString' => $encryptedString,
                    'key' => $key,
                    'key_hash' => bin2hex($key_hash),
                    'iv' => bin2hex($iv)
                ]);

                // Decrypt the base64-encoded encrypted string
                $decryptedString = openssl_decrypt(
                    base64_decode($encryptedString),
                    $encrypt_method,
                    $key_hash,
                    OPENSSL_RAW_DATA, // Bisa coba tambahkan | OPENSSL_ZERO_PADDING jika masih gagal
                    $iv
                );

                Log::info("Hasil dekripsi", [
                    'decryptedString' => $decryptedString
                ]);

                $jsonString = LZString::decompressFromEncodedURIComponent($decryptedString);

                // Jika gagal decompress, log error dan beri respons error
                if ($jsonString === false || $jsonString === null) {
                    Log::error("Gagal decompress", [
                        'decryptedString' => $decryptedString
                    ]);
                    return response()->json(['status' => 'error', 'message' => 'Decompress failed'], 400);
                }
                Log::info("Hasil decompressed", [
                    'jsonString' => $jsonString
                ]);

                // Decompress the string
                $data = json_decode($jsonString, true);

                if ($data !== null) {
                    break;
                }
            } catch (\Exception $e) {
                if ($attempt >= $maxRetries - 1) {
                    return response()->json(['status' => 'error', 'message' => $e->getMessage(), 'response_time' => number_format($responseTime, 2)], 400);
                }
            }
            $attempt++;
        }

        // Check if data is null or empty
        if (empty($data) || !isset($data['list']) || empty($data['list'])) {
            return response()->json(['status' => 'error', 'message' => 'No data found', 'response_time' => number_format($responseTime, 2)], 400);
        }
        return response()->json([
            "data" => $data,
            "response_time" => number_format($responseTime, 2)
        ]);
    }

    public function get_ws_poli_bpjs()
    {
        $config = set_bpjs::find(1);
        $BASE_URL = $config->BASE_URL;
        $SERVICE_NAME = $config->SERVICE_ANTREAN;
        $feature = 'ref/poli/tanggal';
        $params = date('Y-m-d');

        try {
            // Assuming $this->generateHeaders() returns an array of headers
            $headers = array_merge([
                'Content-Type' => 'application/json; charset=utf-8'
            ], $this->get_token()['headers']);

            // Make the API request
            $response = Http::withHeaders($headers)
                ->get("{$BASE_URL}/{$SERVICE_NAME}/{$feature}/{$params}");

            // Decode the response body
            $responseBody = json_decode($response->body(), true);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 400);
        }

        // Fetch the encrypted response data
        if (is_array($responseBody) ) {
            $encryptedString = $responseBody['response'];
        } else {
            return response()->json($responseBody);
        }



        // Decrypt the string using AES-256-CBC
        $key = $this->get_token()['key_decrypt'];
        $encrypt_method = 'AES-256-CBC';
        $key_hash = hex2bin(hash('sha256', $key));  // Get key hash
        $iv = substr(hex2bin(hash('sha256', $key)), 0, 16);  // Get IV

        // Decrypt the base64-encoded encrypted string
        $decryptedString = openssl_decrypt(base64_decode($encryptedString), $encrypt_method, $key_hash, OPENSSL_RAW_DATA, $iv);

        $jsonString = LZString::decompressFromEncodedURIComponent($decryptedString);

        // Decompress the string
        $data = json_decode($jsonString, true);




        return response()->json( $data );
    }

    public function get_ws_dokter_bpjs($kode)
    {
        $config = set_bpjs::find(1);
        $BASE_URL = $config->BASE_URL;
        $SERVICE_NAME = $config->SERVICE_ANTREAN;
        $feature = 'ref/dokter/kodepoli';
        $feature1 = 'tanggal';
        $params = date('Y-m-d');

        try {
            // Assuming $this->generateHeaders() returns an array of headers
            $headers = array_merge([
                'Content-Type' => 'application/json; charset=utf-8'
            ], $this->get_token()['headers']);

            // Make the API request
            $response = Http::withHeaders($headers)
                ->get("{$BASE_URL}/{$SERVICE_NAME}/{$feature}/{$kode}/{$feature1}/{$params}");

            // Decode the response body
            $responseBody = json_decode($response->body(), true);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 400);
        }

        // Fetch the encrypted response data
        if (is_array($responseBody) ) {
            $encryptedString = $responseBody['response'];
        } else {
            return response()->json($responseBody);
        }



        // Decrypt the string using AES-256-CBC
        $key = $this->get_token()['key_decrypt'];
        $encrypt_method = 'AES-256-CBC';
        $key_hash = hex2bin(hash('sha256', $key));  // Get key hash
        $iv = substr(hex2bin(hash('sha256', $key)), 0, 16);  // Get IV

        // Decrypt the base64-encoded encrypted string
        $decryptedString = openssl_decrypt(base64_decode($encryptedString), $encrypt_method, $key_hash, OPENSSL_RAW_DATA, $iv);

        $jsonString = LZString::decompressFromEncodedURIComponent($decryptedString);

        // Decompress the string
        $data = json_decode($jsonString, true);




        return response()->json( $data );
    }

    public function post_ws_antria_bpjs($data)
    {
        $config = set_bpjs::find(1);
        $BASE_URL = $config->BASE_URL;
        $SERVICE_NAME = $config->SERVICE_ANTREAN;
        $feature = 'antrean/add';

        try {
            // Assuming $this->generateHeaders() returns an array of headers
            $headers = array_merge([
                'Content-Type' => 'application/json; charset=utf-8'
            ], $this->get_token()['headers']);

            // Make the API request
            $response = Http::withHeaders($headers)
                ->post("{$BASE_URL}/{$SERVICE_NAME}/{$feature}", $data );

            // Decode the response body
            $responseBody = json_decode($response->body(), true);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 400);
        }

        if (is_array($responseBody) && isset($responseBody['response'])) {
            $encryptedString = $responseBody['response'];
        } else {
            return response()->json([
                'success' => false,
                'message' => $responseBody['metadata']['message'] ?? 'Terjadi kesalahan.',
                'code' => $responseBody['metadata']['code'] ?? null,
            ], 400);
        }



        // Decrypt the string using AES-256-CBC
        $key = $this->get_token()['key_decrypt'];
        $encrypt_method = 'AES-256-CBC';
        $key_hash = hex2bin(hash('sha256', $key));  // Get key hash
        $iv = substr(hex2bin(hash('sha256', $key)), 0, 16);  // Get IV

        // Decrypt the base64-encoded encrypted string
        $decryptedString = openssl_decrypt(base64_decode($encryptedString), $encrypt_method, $key_hash, OPENSSL_RAW_DATA, $iv);

        $jsonString = LZString::decompressFromEncodedURIComponent($decryptedString);

        // Decompress the string
        $data = json_decode($jsonString, true);


        return response()->json( $data );
    }

    public function delete_ws_antria_bpjs($data)
    {
        $config = set_bpjs::find(1);
        $BASE_URL = $config->BASE_URL;
        $SERVICE_NAME = $config->SERVICE_ANTREAN;
        $feature = 'antrean/batal';

        try {
            // Assuming $this->generateHeaders() returns an array of headers
            $headers = array_merge([
                'Content-Type' => 'application/json; charset=utf-8'
            ], $this->get_token()['headers']);

            // Make the API request
            $response = Http::withHeaders($headers)
                ->post("{$BASE_URL}/{$SERVICE_NAME}/{$feature}", $data );

            // Decode the response body
            $responseBody = json_decode($response->body(), true);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 400);
        }

        if (is_array($responseBody) && isset($responseBody['response'])) {
            $encryptedString = $responseBody['response'];
        } else {
            return response()->json([
                'success' => false,
                'message' => $responseBody['metadata']['message'] ?? 'Terjadi kesalahan.',
                'code' => $responseBody['metadata']['code'] ?? null,
            ], 400);
        }



        // Decrypt the string using AES-256-CBC
        $key = $this->get_token()['key_decrypt'];
        $encrypt_method = 'AES-256-CBC';
        $key_hash = hex2bin(hash('sha256', $key));  // Get key hash
        $iv = substr(hex2bin(hash('sha256', $key)), 0, 16);  // Get IV

        // Decrypt the base64-encoded encrypted string
        $decryptedString = openssl_decrypt(base64_decode($encryptedString), $encrypt_method, $key_hash, OPENSSL_RAW_DATA, $iv);

        $jsonString = LZString::decompressFromEncodedURIComponent($decryptedString);

        // Decompress the string
        $data = json_decode($jsonString, true);


        return response()->json( $data );
    }

    public function update_ws_antria_bpjs($data)
    {
        $config = set_bpjs::find(1);
        $BASE_URL = $config->BASE_URL;
        $SERVICE_NAME = $config->SERVICE_ANTREAN;
        $feature = 'antrean/panggil';

        try {
            // Assuming $this->generateHeaders() returns an array of headers
            $headers = array_merge([
                'Content-Type' => 'application/json; charset=utf-8'
            ], $this->get_token()['headers']);

            // Make the API request
            $response = Http::withHeaders($headers)
                ->post("{$BASE_URL}/{$SERVICE_NAME}/{$feature}", $data );

            // Decode the response body
            $responseBody = json_decode($response->body(), true);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 400);
        }

        if (is_array($responseBody) && isset($responseBody['response'])) {
            $encryptedString = $responseBody['response'];
        } else {
            return response()->json([
                'success' => false,
                'message' => $responseBody['metadata']['message'] ?? 'Terjadi kesalahan.',
                'code' => $responseBody['metadata']['code'] ?? null,
            ], 400);
        }



        // Decrypt the string using AES-256-CBC
        $key = $this->get_token()['key_decrypt'];
        $encrypt_method = 'AES-256-CBC';
        $key_hash = hex2bin(hash('sha256', $key));  // Get key hash
        $iv = substr(hex2bin(hash('sha256', $key)), 0, 16);  // Get IV

        // Decrypt the base64-encoded encrypted string
        $decryptedString = openssl_decrypt(base64_decode($encryptedString), $encrypt_method, $key_hash, OPENSSL_RAW_DATA, $iv);

        $jsonString = LZString::decompressFromEncodedURIComponent($decryptedString);

        // Decompress the string
        $data = json_decode($jsonString, true);


        return response()->json( $data );
    }

    public function post_pendaftaran_bpjs($datapost)
    {
        $config = set_bpjs::find(1);
        $BASE_URL = $config->BASE_URL;
        $SERVICE_NAME = $config->SERVICE;
        $feature = 'pendaftaran';
        $maxRetries = 3;
        $attempt = 0;
        $data = null;
        $responseTime = 0;
            try {
                $startTime = microtime(true);
                // Assuming $this->generateHeaders() returns an array of headers
                $headers = array_merge([
                    'Content-Type' => 'text/plain; charset=utf-8'
                ], $this->get_token()['headers']);

                $response = Http::withHeaders($headers)
                    ->post("{$BASE_URL}/{$SERVICE_NAME}/{$feature}", $datapost);

                // Decode response
                $responseBody = json_decode($response->body(), true);
                $endTime = microtime(true);
                $responseTime = $endTime - $startTime;

                // Fetch the encrypted response data
                $encryptedString = $responseBody['response'];

                // Decrypt the string using AES-256-CBC
                $key = $this->get_token()['key_decrypt'];
                $encrypt_method = 'AES-256-CBC';
                $key_hash = substr(hex2bin(hash('sha256', $key)), 0, 32);  // Get key hash
                $iv = substr(hex2bin(hash('sha256', $key)), 0, 16);  // Get IV

                // Log sebelum dekripsi
                Log::info("Mulai proses dekripsi", [
                    'encryptedString' => $encryptedString,
                    'key' => $key,
                    'key_hash' => bin2hex($key_hash),
                    'iv' => bin2hex($iv)
                ]);

                // Decrypt the base64-encoded encrypted string
                $decryptedString = openssl_decrypt(
                    base64_decode($encryptedString),
                    $encrypt_method,
                    $key_hash,
                    OPENSSL_RAW_DATA, // Bisa coba tambahkan | OPENSSL_ZERO_PADDING jika masih gagal
                    $iv
                );

                Log::info("Hasil dekripsi", [
                    'decryptedString' => $decryptedString
                ]);

                $jsonString = LZString::decompressFromEncodedURIComponent($decryptedString);

                // Jika gagal decompress, log error dan beri respons error
                if ($jsonString === false || $jsonString === null) {
                    Log::error("Gagal decompress", [
                        'decryptedString' => $decryptedString
                    ]);
                    return response()->json(['status' => 'error', 'message' => 'Decompress failed'], 400);
                }
                Log::info("Hasil decompressed", [
                    'jsonString' => $jsonString
                ]);

                // Decompress the string
                $data = json_decode($jsonString, true);

            } catch (\Exception $e) {
                if ($attempt >= $maxRetries - 1) {
                    return response()->json(['status' => 'error', 'message' => $e->getMessage(), 'response_time' => number_format($responseTime, 2)], 400);
                }
            }
        // Check if data is null or empty
        if (empty($data)) {
            return response()->json(['status' => 'error', 'message' => 'No data found', 'response_time' => number_format($responseTime, 2)], 400);
        }
        return response()->json([
            "data" => $data,
            "response_time" => number_format($responseTime, 2)
        ]);
    }

    public function get_sarana_bpjs()
    {
        $config = set_bpjs::find(1);
        $BASE_URL = $config->BASE_URL;
        $SERVICE_NAME = $config->SERVICE;
        $feature = 'spesialis/sarana';
        $maxRetries = 3;
        $attempt = 0;
        $data = null;
        $responseTime = 0;

        while ($attempt < $maxRetries && $data === null) {
            try {
                $startTime = microtime(true);

                // Assuming $this->generateHeaders() returns an array of headers
                $headers = array_merge([
                    'Content-Type' => 'application/json; charset=utf-8'
                ], $this->get_token()['headers']);

                // Make the API request
                $response = Http::withHeaders($headers)
                    ->get("{$BASE_URL}/{$SERVICE_NAME}/{$feature}");

                // Decode the response body
                $responseBody = json_decode($response->body(), true);

                $endTime = microtime(true);
                $responseTime = $endTime - $startTime;

                // Fetch the encrypted response data
                $encryptedString = $responseBody['response'];

                // Decrypt the string using AES-256-CBC
                $key = $this->get_token()['key_decrypt'];
                $encrypt_method = 'AES-256-CBC';
                $key_hash = substr(hex2bin(hash('sha256', $key)), 0, 32);  // Get key hash
                $iv = substr(hex2bin(hash('sha256', $key)), 0, 16);  // Get IV

                // Log sebelum dekripsi
                Log::info("Mulai proses dekripsi", [
                    'encryptedString' => $encryptedString,
                    'key' => $key,
                    'key_hash' => bin2hex($key_hash),
                    'iv' => bin2hex($iv)
                ]);

                // Decrypt the base64-encoded encrypted string
                $decryptedString = openssl_decrypt(
                    base64_decode($encryptedString),
                    $encrypt_method,
                    $key_hash,
                    OPENSSL_RAW_DATA, // Bisa coba tambahkan | OPENSSL_ZERO_PADDING jika masih gagal
                    $iv
                );

                Log::info("Hasil dekripsi", [
                    'decryptedString' => $decryptedString
                ]);

                $jsonString = LZString::decompressFromEncodedURIComponent($decryptedString);

                // Jika gagal decompress, log error dan beri respons error
                if ($jsonString === false || $jsonString === null) {
                    Log::error("Gagal decompress", [
                        'decryptedString' => $decryptedString
                    ]);
                    return response()->json(['status' => 'error', 'message' => 'Decompress failed'], 400);
                }
                Log::info("Hasil decompressed", [
                    'jsonString' => $jsonString
                ]);

                // Decompress the string
                $data = json_decode($jsonString, true);

                if ($data !== null) {
                    break;
                }
            } catch (\Exception $e) {
                if ($attempt >= $maxRetries - 1) {
                    return response()->json(['status' => 'error', 'message' => $e->getMessage(), 'response_time' => number_format($responseTime, 2)], 400);
                }
            }
            $attempt++;
        }

        // Check if data is null or empty
        if (empty($data) || !isset($data['list']) || empty($data['list'])) {
            return response()->json(['status' => 'error', 'message' => 'No data found', 'response_time' => number_format($responseTime, 2)], 400);
        }
        return response()->json([
            "data" => $data,
            "response_time" => number_format($responseTime, 2)
        ]);
    }

    public function get_rujukan_spesialis_bpjs($spesialis,$sarana,$tanggal)
    {
        $config = set_bpjs::find(1);
        $BASE_URL = $config->BASE_URL;
        $SERVICE_NAME = $config->SERVICE;
        $feature = 'spesialis/rujuk/subspesialis';
        $maxRetries = 3;
        $attempt = 0;
        $data = null;
        $responseTime = 0;

        while ($attempt < $maxRetries && $data === null) {
            try {
                $startTime = microtime(true);

                // Assuming $this->generateHeaders() returns an array of headers
                $headers = array_merge([
                    'Content-Type' => 'application/json; charset=utf-8'
                ], $this->get_token()['headers']);

                // Make the API request
                $response = Http::withHeaders($headers)
                    ->get("{$BASE_URL}/{$SERVICE_NAME}/{$feature}/{$spesialis}/sarana/{$sarana}/tglEstRujuk/{$tanggal}");

                // Decode the response body
                $responseBody = json_decode($response->body(), true);

                $endTime = microtime(true);
                $responseTime = $endTime - $startTime;

                // Fetch the encrypted response data
                $encryptedString = $responseBody['response'];

                // Decrypt the string using AES-256-CBC
                $key = $this->get_token()['key_decrypt'];
                $encrypt_method = 'AES-256-CBC';
                $key_hash = substr(hex2bin(hash('sha256', $key)), 0, 32);  // Get key hash
                $iv = substr(hex2bin(hash('sha256', $key)), 0, 16);  // Get IV

                // Log sebelum dekripsi
                Log::info("Mulai proses dekripsi", [
                    'encryptedString' => $encryptedString,
                    'key' => $key,
                    'key_hash' => bin2hex($key_hash),
                    'iv' => bin2hex($iv)
                ]);

                // Decrypt the base64-encoded encrypted string
                $decryptedString = openssl_decrypt(
                    base64_decode($encryptedString),
                    $encrypt_method,
                    $key_hash,
                    OPENSSL_RAW_DATA, // Bisa coba tambahkan | OPENSSL_ZERO_PADDING jika masih gagal
                    $iv
                );

                Log::info("Hasil dekripsi", [
                    'decryptedString' => $decryptedString
                ]);

                $jsonString = LZString::decompressFromEncodedURIComponent($decryptedString);

                // Jika gagal decompress, log error dan beri respons error
                if ($jsonString === false || $jsonString === null) {
                    Log::error("Gagal decompress", [
                        'decryptedString' => $decryptedString
                    ]);
                    return response()->json(['status' => 'error', 'message' => 'Decompress failed'], 400);
                }
                Log::info("Hasil decompressed", [
                    'jsonString' => $jsonString
                ]);

                // Decompress the string
                $data = json_decode($jsonString, true);

                if ($data !== null) {
                    break;
                }
            } catch (\Exception $e) {
                if ($attempt >= $maxRetries - 1) {
                    return response()->json(['status' => 'error', 'message' => $e->getMessage(), 'response_time' => number_format($responseTime, 2)], 400);
                }
            }
            $attempt++;
        }

        // Check if data is null or empty
        if (empty($data) || !isset($data['list']) || empty($data['list'])) {
            return response()->json(['status' => 'error', 'message' => 'No data found', 'response_time' => number_format($responseTime, 2)], 400);
        }
        return response()->json([
            "data" => $data,
            "response_time" => number_format($responseTime, 2)
        ]);
    }

    public function get_rujukan_husus_bpjs($spesialis,$noKartu,$tanggal)
    {
        $config = set_bpjs::find(1);
        $BASE_URL = $config->BASE_URL;
        $SERVICE_NAME = $config->SERVICE;
        $feature = 'spesialis/rujuk/khusus';
        $maxRetries = 3;
        $attempt = 0;
        $data = null;
        $responseTime = 0;

        while ($attempt < $maxRetries && $data === null) {
            try {
                $startTime = microtime(true);

                // Assuming $this->generateHeaders() returns an array of headers
                $headers = array_merge([
                    'Content-Type' => 'application/json; charset=utf-8'
                ], $this->get_token()['headers']);

                // Make the API request
                $response = Http::withHeaders($headers)
                    ->get("{$BASE_URL}/{$SERVICE_NAME}/{$feature}/{$spesialis}/noKartu/{$noKartu}/tglEstRujuk/{$tanggal}");

                // Decode the response body
                $responseBody = json_decode($response->body(), true);

                $endTime = microtime(true);
                $responseTime = $endTime - $startTime;

                // Fetch the encrypted response data
                $encryptedString = $responseBody['response'];

                // Decrypt the string using AES-256-CBC
                $key = $this->get_token()['key_decrypt'];
                $encrypt_method = 'AES-256-CBC';
                $key_hash = substr(hex2bin(hash('sha256', $key)), 0, 32);  // Get key hash
                $iv = substr(hex2bin(hash('sha256', $key)), 0, 16);  // Get IV

                // Log sebelum dekripsi
                Log::info("Mulai proses dekripsi", [
                    'encryptedString' => $encryptedString,
                    'key' => $key,
                    'key_hash' => bin2hex($key_hash),
                    'iv' => bin2hex($iv)
                ]);

                // Decrypt the base64-encoded encrypted string
                $decryptedString = openssl_decrypt(
                    base64_decode($encryptedString),
                    $encrypt_method,
                    $key_hash,
                    OPENSSL_RAW_DATA, // Bisa coba tambahkan | OPENSSL_ZERO_PADDING jika masih gagal
                    $iv
                );

                Log::info("Hasil dekripsi", [
                    'decryptedString' => $decryptedString
                ]);

                $jsonString = LZString::decompressFromEncodedURIComponent($decryptedString);

                // Jika gagal decompress, log error dan beri respons error
                if ($jsonString === false || $jsonString === null) {
                    Log::error("Gagal decompress", [
                        'decryptedString' => $decryptedString
                    ]);
                    return response()->json(['status' => 'error', 'message' => 'Decompress failed'], 400);
                }
                Log::info("Hasil decompressed", [
                    'jsonString' => $jsonString
                ]);

                // Decompress the string
                $data = json_decode($jsonString, true);

                if ($data !== null) {
                    break;
                }
            } catch (\Exception $e) {
                if ($attempt >= $maxRetries - 1) {
                    return response()->json(['status' => 'error', 'message' => $e->getMessage(), 'response_time' => number_format($responseTime, 2)], 400);
                }
            }
            $attempt++;
        }

        // Check if data is null or empty
        if (empty($data) || !isset($data['list']) || empty($data['list'])) {
            return response()->json(['status' => 'error', 'message' => 'No data found', 'response_time' => number_format($responseTime, 2)], 400);
        }
        return response()->json([
            "data" => $data,
            "response_time" => number_format($responseTime, 2)
        ]);
    }

    public function get_rujukan_husus_subspesialis_bpjs($husus,$spesialis,$noKartu,$tanggal)
    {
        $config = set_bpjs::find(1);
        $BASE_URL = $config->BASE_URL;
        $SERVICE_NAME = $config->SERVICE;
        $feature = 'spesialis/rujuk/khusus';
        $maxRetries = 3;
        $attempt = 0;
        $data = null;
        $responseTime = 0;

        while ($attempt < $maxRetries && $data === null) {
            try {
                $startTime = microtime(true);

                // Assuming $this->generateHeaders() returns an array of headers
                $headers = array_merge([
                    'Content-Type' => 'application/json; charset=utf-8'
                ], $this->get_token()['headers']);

                // Make the API request
                $response = Http::withHeaders($headers)
                    ->get("{$BASE_URL}/{$SERVICE_NAME}/{$feature}/{$spesialis}/subspesialis/{$husus}/noKartu/{$noKartu}/tglEstRujuk/{$tanggal}");

                // Decode the response body
                $responseBody = json_decode($response->body(), true);

                $endTime = microtime(true);
                $responseTime = $endTime - $startTime;

                // Fetch the encrypted response data
                $encryptedString = $responseBody['response'];

                // Decrypt the string using AES-256-CBC
                $key = $this->get_token()['key_decrypt'];
                $encrypt_method = 'AES-256-CBC';
                $key_hash = substr(hex2bin(hash('sha256', $key)), 0, 32);  // Get key hash
                $iv = substr(hex2bin(hash('sha256', $key)), 0, 16);  // Get IV

                // Log sebelum dekripsi
                Log::info("Mulai proses dekripsi", [
                    'encryptedString' => $encryptedString,
                    'key' => $key,
                    'key_hash' => bin2hex($key_hash),
                    'iv' => bin2hex($iv)
                ]);

                // Decrypt the base64-encoded encrypted string
                $decryptedString = openssl_decrypt(
                    base64_decode($encryptedString),
                    $encrypt_method,
                    $key_hash,
                    OPENSSL_RAW_DATA, // Bisa coba tambahkan | OPENSSL_ZERO_PADDING jika masih gagal
                    $iv
                );

                Log::info("Hasil dekripsi", [
                    'decryptedString' => $decryptedString
                ]);

                $jsonString = LZString::decompressFromEncodedURIComponent($decryptedString);

                // Jika gagal decompress, log error dan beri respons error
                if ($jsonString === false || $jsonString === null) {
                    Log::error("Gagal decompress", [
                        'decryptedString' => $decryptedString
                    ]);
                    return response()->json(['status' => 'error', 'message' => 'Decompress failed'], 400);
                }
                Log::info("Hasil decompressed", [
                    'jsonString' => $jsonString
                ]);

                // Decompress the string
                $data = json_decode($jsonString, true);

                if ($data !== null) {
                    break;
                }
            } catch (\Exception $e) {
                if ($attempt >= $maxRetries - 1) {
                    return response()->json(['status' => 'error', 'message' => $e->getMessage(), 'response_time' => number_format($responseTime, 2)], 400);
                }
            }
            $attempt++;
        }

        // Check if data is null or empty
        if (empty($data) || !isset($data['list']) || empty($data['list'])) {
            return response()->json(['status' => 'error', 'message' => 'No data found', 'response_time' => number_format($responseTime, 2)], 400);
        }
        return response()->json([
            "data" => $data,
            "response_time" => number_format($responseTime, 2)
        ]);
    }
}
