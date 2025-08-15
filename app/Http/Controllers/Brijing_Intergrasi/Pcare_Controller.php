<?php

namespace App\Http\Controllers\Brijing_Intergrasi;

use App\Http\Controllers\Controller;

use App\Models\dokter;
use App\Models\dokter_jadwal;
use App\Models\Set_Bpjs;
use App\Models\WebSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use LZCompressor\LZString;
use Illuminate\Support\Facades\Log;

class Pcare_Controller extends Controller
{
    public function get_token()
    {
        $this->checkBpjsActive();
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
        $this->checkBpjsActive();
        $config = set_bpjs::find(1);
        $BASE_URL = $config->BASE_URL;
        $SERVICE_NAME = $config->SERVICE;
        $feature = 'peserta';
        $maxDecryptRetries = 3;
        $responseTime = 0;
        $data = null;
        $token = $this->get_token();

        try {
            $startTime = microtime(true);
            $headers = array_merge([
                'Content-Type' => 'application/json; charset=utf-8'
            ], $token['headers']);
            $timestamp = $token['headers']['X-Timestamp'];
            $key = $token['key_decrypt'];

            $response = Http::withHeaders($headers)
                ->get("{$BASE_URL}/{$SERVICE_NAME}/{$feature}/{$noka}");

            $responseBody = json_decode($response->body(), true);
            $responseTime = microtime(true) - $startTime;

            if (!is_array($responseBody)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Respon BPJS tidak valid',
                    'response_time' => number_format($responseTime, 2)
                ], 400);
            }

            if (isset($responseBody['metadata']) && $responseBody['metadata']['code'] != 200) {
                return response()->json([
                    'status' => 'error',
                    'message' => $responseBody['metadata']['message'] ?? 'Permintaan BPJS gagal',
                    'response_time' => number_format($responseTime, 2)
                ], 400);
            }

            if (!isset($responseBody['response'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Field response tidak ditemukan',
                    'response_time' => number_format($responseTime, 2)
                ], 400);
            }

            $encryptedString = $responseBody['response'];
            $encrypt_method = 'AES-256-CBC';
            $key_hash = hash('sha256', $key, true);
            $iv = substr($key_hash, 0, 16);

            for ($decAttempt = 0; $decAttempt < $maxDecryptRetries; $decAttempt++) {
                $decryptedString = openssl_decrypt(
                    base64_decode($encryptedString),
                    $encrypt_method,
                    $key_hash,
                    OPENSSL_RAW_DATA,
                    $iv
                );

                if ($decryptedString) {
                    $jsonString = LZString::decompressFromEncodedURIComponent($decryptedString);
                    if ($jsonString) {
                        $data = json_decode($jsonString, true);
                        if ($data !== null) {
                            break;
                        }
                    }
                }

                Log::warning("Dekripsi NOKA gagal percobaan ke-{$decAttempt}");
            }

            // Fallback jika dekripsi gagal
            if ($data === null) {
                Log::warning("Fallback ke bpjs_dekrip_internal");
                $fallback = $this->bpjs_dekrip_internal($timestamp, $encryptedString);
                if (isset($fallback['data'])) {
                    $data = $fallback['data'];
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'response_time' => number_format($responseTime, 2)
            ], 400);
        }

        if (empty($data)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan atau kosong',
                'response_time' => number_format($responseTime, 2)
            ], 400);
        }

        return response()->json([
            'status' => 'success',
            'data' => $data,
            'response_time' => number_format($responseTime, 2)
        ]);
    }


    public function get_nik_bpjs($nik)
    {
        $this->checkBpjsActive();
        $config = set_bpjs::find(1);
        if (!$config) {
            return response()->json(['status' => 'error', 'message' => 'Config not found'], 500);
        }

        $BASE_URL = $config->BASE_URL;
        $SERVICE_NAME = $config->SERVICE;
        $feature = 'peserta/nik';
        $maxDecryptRetries = 3;
        $responseTime = 0;
        $data = null;
        $token = $this->get_token();

        try {
            $startTime = microtime(true);

            if (!$token || !isset($token['headers'], $token['key_decrypt'])) {
                return response()->json(['status' => 'error', 'message' => 'Token retrieval failed'], 500);
            }

            $headers = array_merge([
                'Content-Type' => 'application/json; charset=utf-8'
            ], $token['headers']);
            $timestamp = $token['headers']['X-Timestamp'];
            $key = $token['key_decrypt'];

            $response = Http::withHeaders($headers)->get("{$BASE_URL}/{$SERVICE_NAME}/{$feature}/{$nik}");
            $responseTime = microtime(true) - $startTime;

            $responseBody = json_decode($response->body(), true);

            if (!is_array($responseBody)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid BPJS response format',
                    'response_time' => number_format($responseTime, 2)
                ], 400);
            }

            if (isset($responseBody['metadata']) && $responseBody['metadata']['code'] != 200) {
                return response()->json([
                    'status' => 'error',
                    'message' => $responseBody['metadata']['message'] ?? 'BPJS request failed',
                    'response_time' => number_format($responseTime, 2)
                ], 400);
            }

            if (!isset($responseBody['response'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Field "response" not found',
                    'response_time' => number_format($responseTime, 2)
                ], 400);
            }

            $encryptedString = $responseBody['response'];
            $encrypt_method = 'AES-256-CBC';
            $key_hash = hash('sha256', $key, true);
            $iv = substr($key_hash, 0, 16);

            for ($decAttempt = 0; $decAttempt < $maxDecryptRetries; $decAttempt++) {
                $decryptedString = openssl_decrypt(
                    base64_decode($encryptedString),
                    $encrypt_method,
                    $key_hash,
                    OPENSSL_RAW_DATA,
                    $iv
                );

                if ($decryptedString) {
                    $jsonString = LZString::decompressFromEncodedURIComponent($decryptedString);
                    if ($jsonString) {
                        $data = json_decode($jsonString, true);
                        if ($data !== null) {
                            break;
                        }
                    }
                }

                Log::warning("Dekripsi NIK gagal percobaan ke-{$decAttempt}");
            }

            // Fallback jika dekripsi gagal
            if ($data === null) {
                Log::warning("Fallback ke bpjs_dekrip_internal");
                $fallback = $this->bpjs_dekrip_internal($timestamp, $encryptedString);
                if (isset($fallback['data'])) {
                    $data = $fallback['data'];
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'response_time' => number_format($responseTime, 2)
            ], 400);
        }

        if (empty($data)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan atau kosong',
                'response_time' => number_format($responseTime, 2)
            ], 400);
        }

        return response()->json([
            'status' => 'success',
            'data' => $data,
            'response_time' => number_format($responseTime, 2)
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
        $maxRequestRetries = 3;
        $maxDecryptRetries = 3;
        $responseTime = 0;
        $data = null;
        $token = $this->get_token();

        for ($reqAttempt = 0; $reqAttempt < $maxRequestRetries; $reqAttempt++) {
            try {
                $startTime = microtime(true);

                $headers = array_merge([
                    'Content-Type' => 'application/json; charset=utf-8'
                ], $token['headers']);
                $timestamp = $token['headers']['X-Timestamp'];
                $key = $token['key_decrypt'];

                $response = Http::withHeaders($headers)
                    ->get("{$BASE_URL}/{$SERVICE_NAME}/{$feature}/{$params}/{$params1}");

                $responseBody = json_decode($response->body(), true);
                $responseTime = microtime(true) - $startTime;

                if (!is_array($responseBody)) {
                    Log::warning("BPJS response bukan array", ['body' => $response->body()]);
                    continue;
                }

                if (isset($responseBody['metadata']) && $responseBody['metadata']['code'] != 200) {
                    return response()->json([
                        'status' => 'error',
                        'message' => $responseBody['metadata']['message'] ?? 'Permintaan BPJS gagal',
                        'response_time' => number_format($responseTime, 2)
                    ], 400);
                }

                if (!isset($responseBody['response'])) {
                    Log::warning("BPJS response tidak mengandung 'response'", ['responseBody' => $responseBody]);
                    continue;
                }

                $encryptedString = $responseBody['response'];
                $encrypt_method = 'AES-256-CBC';
                $key_hash = hash('sha256', $key, true);
                $iv = substr($key_hash, 0, 16);

                for ($decAttempt = 0; $decAttempt < $maxDecryptRetries; $decAttempt++) {
                    $decryptedString = openssl_decrypt(
                        base64_decode($encryptedString),
                        $encrypt_method,
                        $key_hash,
                        OPENSSL_RAW_DATA,
                        $iv
                    );

                    if ($decryptedString) {
                        $jsonString = LZString::decompressFromEncodedURIComponent($decryptedString);

                        if ($jsonString) {
                            $data = json_decode($jsonString, true);
                            if ($data !== null) {
                                break 2; // sukses: keluar dari 2 loop
                            }
                        }
                    }

                    Log::warning("Percobaan dekripsi ke-{$decAttempt} gagal");
                }

                // Jika gagal semua percobaan dekripsi, coba fallback
                Log::warning("Gagal dekripsi 3x, fallback ke bpjs_dekrip");
                $fallback = $this->bpjs_dekrip_internal($timestamp, $encryptedString);
                if (isset($fallback['data'])) {
                    $data = $fallback['data'];
                    break;
                }
            } catch (\Exception $e) {
                Log::error("Error: " . $e->getMessage());
                if ($reqAttempt >= $maxRequestRetries - 1) {
                    return response()->json([
                        'status' => 'error',
                        'message' => $e->getMessage(),
                        'response_time' => number_format($responseTime, 2)
                    ], 400);
                }
            }
        }

        if (empty($data) || !isset($data['list']) || empty($data['list'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'No data found',
                'response_time' => number_format($responseTime, 2)
            ], 400);
        }

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
        $maxRequestRetries = 3;
        $maxDecryptRetries = 3;
        $attempt = 0;
        $data = null;
        $responseTime = 0;
        $token = $this->get_token();

        for ($reqAttempt = 0; $reqAttempt < $maxRequestRetries; $reqAttempt++) {
            try {
                $startTime = microtime(true);

                $headers = array_merge([
                    'Content-Type' => 'application/json; charset=utf-8'
                ], $token['headers']);
                $timestamp = $token['headers']['X-Timestamp'];
                $key = $token['key_decrypt'];

                $response = Http::withHeaders($headers)
                    ->get("{$BASE_URL}/{$SERVICE_NAME}/{$feature}/{$params}/{$params1}");

                $responseBody = json_decode($response->body(), true);
                $responseTime = microtime(true) - $startTime;

                if (!is_array($responseBody)) {
                    Log::warning("BPJS response bukan array", ['body' => $response->body()]);
                    continue;
                }

                if (isset($responseBody['metadata']) && $responseBody['metadata']['code'] != 200) {
                    return response()->json([
                        'status' => 'error',
                        'message' => $responseBody['metadata']['message'] ?? 'Permintaan BPJS gagal',
                        'response_time' => number_format($responseTime, 2)
                    ], 400);
                }

                if (!isset($responseBody['response'])) {
                    Log::warning("BPJS response tidak mengandung 'response'", ['responseBody' => $responseBody]);
                    continue;
                }

                $encryptedString = $responseBody['response'];
                $encrypt_method = 'AES-256-CBC';
                $key_hash = hash('sha256', $key, true);
                $iv = substr($key_hash, 0, 16);

                for ($decAttempt = 0; $decAttempt < $maxDecryptRetries; $decAttempt++) {
                    $decryptedString = openssl_decrypt(
                        base64_decode($encryptedString),
                        $encrypt_method,
                        $key_hash,
                        OPENSSL_RAW_DATA,
                        $iv
                    );

                    if ($decryptedString) {
                        $jsonString = LZString::decompressFromEncodedURIComponent($decryptedString);

                        if ($jsonString) {
                            $data = json_decode($jsonString, true);
                            if ($data !== null) {
                                break 2; // keluar dari kedua loop
                            }
                        }
                    }

                    Log::warning("Percobaan dekripsi ke-{$decAttempt} gagal");
                }

                Log::warning("Gagal dekripsi 3x, fallback ke bpjs_dekrip");
                $fallback = $this->bpjs_dekrip_internal($timestamp, $encryptedString);
                if (isset($fallback['data'])) {
                    $data = $fallback['data'];
                    break;
                }
            } catch (\Exception $e) {
                Log::error("Error: " . $e->getMessage());
                if ($reqAttempt >= $maxRequestRetries - 1) {
                    return response()->json([
                        'status' => 'error',
                        'message' => $e->getMessage(),
                        'response_time' => number_format($responseTime, 2)
                    ], 400);
                }
            }
        }

        if (empty($data) || !isset($data['list']) || empty($data['list'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'No data found',
                'response_time' => number_format($responseTime, 2)
            ], 400);
        }

        return response()->json([
            'data' => $data,
            'response_time' => number_format($responseTime, 2)
        ]);
    }


    public function get_spesialis_bpjs()
    {
        $config = set_bpjs::find(1);
        $BASE_URL = $config->BASE_URL;
        $SERVICE_NAME = $config->SERVICE;
        $feature = 'spesialis';
        $maxRequestRetries = 3;
        $maxDecryptRetries = 3;
        $responseTime = 0;
        $data = null;
        $token = $this->get_token();

        for ($reqAttempt = 0; $reqAttempt < $maxRequestRetries; $reqAttempt++) {
            try {
                $startTime = microtime(true);

                $headers = array_merge([
                    'Content-Type' => 'application/json; charset=utf-8'
                ], $token['headers']);
                $timestamp = $token['headers']['X-Timestamp'];
                $key = $token['key_decrypt'];

                $response = Http::withHeaders($headers)
                    ->get("{$BASE_URL}/{$SERVICE_NAME}/{$feature}");

                $responseBody = json_decode($response->body(), true);
                $responseTime = microtime(true) - $startTime;

                if (!is_array($responseBody)) {
                    Log::warning("BPJS response bukan array", ['body' => $response->body()]);
                    continue;
                }

                if (isset($responseBody['metadata']) && $responseBody['metadata']['code'] != 200) {
                    return response()->json([
                        'status' => 'error',
                        'message' => $responseBody['metadata']['message'] ?? 'Permintaan BPJS gagal',
                        'response_time' => number_format($responseTime, 2)
                    ], 400);
                }

                if (!isset($responseBody['response'])) {
                    Log::warning("BPJS response tidak mengandung 'response'", ['responseBody' => $responseBody]);
                    continue;
                }

                $encryptedString = $responseBody['response'];
                $encrypt_method = 'AES-256-CBC';
                $key_hash = hash('sha256', $key, true);
                $iv = substr($key_hash, 0, 16);

                for ($decAttempt = 0; $decAttempt < $maxDecryptRetries; $decAttempt++) {
                    $decryptedString = openssl_decrypt(
                        base64_decode($encryptedString),
                        $encrypt_method,
                        $key_hash,
                        OPENSSL_RAW_DATA,
                        $iv
                    );

                    if ($decryptedString) {
                        $jsonString = LZString::decompressFromEncodedURIComponent($decryptedString);

                        if ($jsonString) {
                            $data = json_decode($jsonString, true);
                            if ($data !== null) {
                                break 2; // Keluar dari kedua loop
                            }
                        }
                    }

                    Log::warning("Percobaan dekripsi ke-{$decAttempt} gagal");
                }

                // Fallback
                Log::warning("Gagal dekripsi 3x, fallback ke bpjs_dekrip_internal");
                $fallback = $this->bpjs_dekrip_internal($timestamp, $encryptedString);
                if (isset($fallback['data'])) {
                    $data = $fallback['data'];
                    break;
                }
            } catch (\Exception $e) {
                Log::error("Error: " . $e->getMessage());
                if ($reqAttempt >= $maxRequestRetries - 1) {
                    return response()->json([
                        'status' => 'error',
                        'message' => $e->getMessage(),
                        'response_time' => number_format($responseTime, 2)
                    ], 400);
                }
            }
        }

        if (empty($data) || !isset($data['list']) || empty($data['list'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'No data found',
                'response_time' => number_format($responseTime, 2)
            ], 400);
        }

        return response()->json([
            'data' => $data,
            'response_time' => number_format($responseTime, 2)
        ]);
    }


    public function get_sub_spesialis_bpjs($nama)
    {
        $config = set_bpjs::find(1);
        if (!$config) {
            return response()->json(['status' => 'error', 'message' => 'Config not found'], 500);
        }

        $BASE_URL = $config->BASE_URL;
        $SERVICE_NAME = $config->SERVICE;
        $feature = 'spesialis';
        $params1 = 'subspesialis';
        $maxDecryptRetries = 3;
        $responseTime = 0;
        $data = null;
        $token = $this->get_token();

        try {
            $startTime = microtime(true);

            if (!$token || !isset($token['headers'], $token['key_decrypt'])) {
                return response()->json(['status' => 'error', 'message' => 'Token retrieval failed'], 500);
            }

            $headers = array_merge([
                'Content-Type' => 'application/json; charset=utf-8'
            ], $token['headers']);
            $timestamp = $token['headers']['X-Timestamp'];
            $key = $token['key_decrypt'];

            // Kirim permintaan API
            $response = Http::withHeaders($headers)
                ->get("{$BASE_URL}/{$SERVICE_NAME}/{$feature}/{$nama}/{$params1}");

            $responseTime = microtime(true) - $startTime;

            $responseBody = json_decode($response->body(), true);
            if (!is_array($responseBody)) {
                return response()->json(['status' => 'error', 'message' => 'Invalid response format', 'response_time' => number_format($responseTime, 2)], 400);
            }

            if (isset($responseBody['metadata']) && $responseBody['metadata']['code'] != 200) {
                return response()->json([
                    'status' => 'error',
                    'message' => $responseBody['metadata']['message'] ?? 'BPJS request failed',
                    'response_time' => number_format($responseTime, 2)
                ], 400);
            }

            if (!isset($responseBody['response'])) {
                return response()->json(['status' => 'error', 'message' => 'Field "response" not found', 'response_time' => number_format($responseTime, 2)], 400);
            }

            $encryptedString = $responseBody['response'];

            // Dekripsi dengan retry
            $encrypt_method = 'AES-256-CBC';
            $key_hash = hash('sha256', $key, true);
            $iv = substr($key_hash, 0, 16);

            for ($i = 0; $i < $maxDecryptRetries; $i++) {
                $decryptedString = openssl_decrypt(
                    base64_decode($encryptedString),
                    $encrypt_method,
                    $key_hash,
                    OPENSSL_RAW_DATA,
                    $iv
                );

                if ($decryptedString) {
                    $jsonString = LZString::decompressFromEncodedURIComponent($decryptedString);
                    if ($jsonString) {
                        $data = json_decode($jsonString, true);
                        if ($data !== null) {
                            break;
                        }
                    }
                }

                Log::warning("Dekripsi subspesialis gagal percobaan ke-" . ($i + 1));
            }

            // Fallback jika semua dekripsi gagal
            if ($data === null) {
                Log::warning("Fallback ke bpjs_dekrip_internal");
                $fallback = $this->bpjs_dekrip_internal($timestamp, $encryptedString);
                if (isset($fallback['data'])) {
                    $data = $fallback['data'];
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'response_time' => number_format($responseTime, 2)
            ], 400);
        }

        // Validasi hasil akhir
        if (empty($data) || !isset($data['list']) || empty($data['list'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'No data found',
                'response_time' => number_format($responseTime, 2)
            ], 400);
        }

        return response()->json([
            'status' => 'success',
            'data' => $data,
            'response_time' => number_format($responseTime, 2)
        ]);
    }


    public function get_diagnosis_bpjs($nama)
    {
        $config = set_bpjs::find(1);
        if (!$config) {
            return response()->json(['status' => 'error', 'message' => 'Config not found'], 500);
        }

        $BASE_URL = $config->BASE_URL;
        $SERVICE_NAME = $config->SERVICE;
        $endpoint = "diagnosa/{$nama}/0/500";

        $maxRequest = 3;
        $maxDecrypt = 3;
        $responseTime = 0;
        $data = null;
        $token = $this->get_token();

        try {
            if (!$token || !isset($token['headers'], $token['key_decrypt'])) {
                return response()->json(['status' => 'error', 'message' => 'Token retrieval failed'], 500);
            }

            $headers = array_merge([
                'Content-Type' => 'application/json; charset=utf-8'
            ], $token['headers']);
            $timestamp = $token['headers']['X-Timestamp'];
            $key = $token['key_decrypt'];

            for ($i = 0; $i < $maxRequest; $i++) {
                $startTime = microtime(true);
                $response = Http::withHeaders($headers)->get("{$BASE_URL}/{$SERVICE_NAME}/{$endpoint}");
                $responseTime = microtime(true) - $startTime;

                $responseBody = json_decode($response->body(), true);
                if (!is_array($responseBody)) {
                    continue;
                }

                if (isset($responseBody['metadata']) && $responseBody['metadata']['code'] != 200) {
                    return response()->json([
                        'status' => 'error',
                        'message' => $responseBody['metadata']['message'] ?? 'BPJS request failed',
                        'response_time' => number_format($responseTime, 2)
                    ], 400);
                }

                if (!isset($responseBody['response'])) {
                    continue;
                }

                $encryptedString = $responseBody['response'];
                $encrypt_method = 'AES-256-CBC';
                $key_hash = hash('sha256', $key, true);
                $iv = substr($key_hash, 0, 16);

                for ($j = 0; $j < $maxDecrypt; $j++) {
                    $decryptedString = openssl_decrypt(
                        base64_decode($encryptedString),
                        $encrypt_method,
                        $key_hash,
                        OPENSSL_RAW_DATA,
                        $iv
                    );

                    if ($decryptedString) {
                        $jsonString = LZString::decompressFromEncodedURIComponent($decryptedString);
                        if ($jsonString) {
                            $data = json_decode($jsonString, true);
                            if ($data !== null) {
                                break 2; // keluar dari kedua loop
                            }
                        }
                    }

                    Log::warning("Dekripsi diagnosis gagal attempt {$j} (request {$i})");
                }

                // fallback dekrip internal
                Log::warning("Fallback dekrip internal - Diagnosis (request {$i})");
                $fallback = $this->bpjs_dekrip_internal($timestamp, $encryptedString);
                if (isset($fallback['data'])) {
                    $data = $fallback['data'];
                    break;
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'response_time' => number_format($responseTime, 2)
            ], 400);
        }

        if (empty($data) || !isset($data['list']) || empty($data['list'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'No data found',
                'response_time' => number_format($responseTime, 2)
            ], 400);
        }

        return response()->json([
            'status' => 'success',
            'data' => $data,
            'response_time' => number_format($responseTime, 2)
        ]);
    }


    public function get_statpul_bpjs($nama)
    {
        $config = set_bpjs::find(1);
        if (!$config) {
            return response()->json(['status' => 'error', 'message' => 'Config not found'], 500);
        }

        $BASE_URL = $config->BASE_URL;
        $SERVICE_NAME = $config->SERVICE;
        $endpoint = "statuspulang/rawatInap/{$nama}";

        $maxRequest = 3;
        $maxDecrypt = 3;
        $responseTime = 0;
        $data = null;
        $token = $this->get_token();

        try {
            if (!$token || !isset($token['headers'], $token['key_decrypt'])) {
                return response()->json(['status' => 'error', 'message' => 'Token retrieval failed'], 500);
            }

            $headers = array_merge([
                'Content-Type' => 'application/json; charset=utf-8'
            ], $token['headers']);
            $timestamp = $token['headers']['X-Timestamp'];
            $key = $token['key_decrypt'];

            for ($i = 0; $i < $maxRequest; $i++) {
                $startTime = microtime(true);
                $response = Http::withHeaders($headers)->get("{$BASE_URL}/{$SERVICE_NAME}/{$endpoint}");
                $responseTime = microtime(true) - $startTime;

                $responseBody = json_decode($response->body(), true);
                if (!is_array($responseBody)) {
                    continue;
                }

                if (isset($responseBody['metadata']) && $responseBody['metadata']['code'] != 200) {
                    return response()->json([
                        'status' => 'error',
                        'message' => $responseBody['metadata']['message'] ?? 'BPJS request failed',
                        'response_time' => number_format($responseTime, 2)
                    ], 400);
                }

                if (!isset($responseBody['response'])) {
                    continue;
                }

                $encryptedString = $responseBody['response'];
                $encrypt_method = 'AES-256-CBC';
                $key_hash = hash('sha256', $key, true);
                $iv = substr($key_hash, 0, 16);

                for ($j = 0; $j < $maxDecrypt; $j++) {
                    $decryptedString = openssl_decrypt(
                        base64_decode($encryptedString),
                        $encrypt_method,
                        $key_hash,
                        OPENSSL_RAW_DATA,
                        $iv
                    );

                    if ($decryptedString) {
                        $jsonString = LZString::decompressFromEncodedURIComponent($decryptedString);
                        if ($jsonString) {
                            $data = json_decode($jsonString, true);
                            if ($data !== null) {
                                break 2;
                            }
                        }
                    }

                    Log::warning("Dekripsi status pulang gagal attempt {$j} (request {$i})");
                }

                // fallback dekrip internal
                Log::warning("Fallback dekrip internal - Status Pulang (request {$i})");
                $fallback = $this->bpjs_dekrip_internal($timestamp, $encryptedString);
                if (isset($fallback['data'])) {
                    $data = $fallback['data'];
                    break;
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'response_time' => number_format($responseTime, 2)
            ], 400);
        }

        if (empty($data) || !isset($data['list']) || empty($data['list'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'No data found',
                'response_time' => number_format($responseTime, 2)
            ], 400);
        }

        return response()->json([
            'status' => 'success',
            'data' => $data,
            'response_time' => number_format($responseTime, 2)
        ]);
    }


    public function get_kesadaran_bpjs()
    {
        $config = set_bpjs::find(1);
        $BASE_URL = $config->BASE_URL;
        $SERVICE_NAME = $config->SERVICE;
        $feature = 'kesadaran';
        $maxRequestRetries = 3;
        $maxDecryptRetries = 3;
        $data = null;
        $responseTime = 0;
        $token = $this->get_token();

        for ($reqAttempt = 0; $reqAttempt < $maxRequestRetries; $reqAttempt++) {
            try {
                $startTime = microtime(true);

                $headers = array_merge([
                    'Content-Type' => 'application/json; charset=utf-8'
                ], $token['headers']);
                $timestamp = $token['headers']['X-Timestamp'];
                $key = $token['key_decrypt'];

                $response = Http::withHeaders($headers)
                    ->get("{$BASE_URL}/{$SERVICE_NAME}/{$feature}");

                $responseBody = json_decode($response->body(), true);
                $responseTime = microtime(true) - $startTime;

                if (!is_array($responseBody)) {
                    Log::warning("BPJS response bukan array", ['body' => $response->body()]);
                    continue;
                }

                if (isset($responseBody['metadata']) && $responseBody['metadata']['code'] != 200) {
                    return response()->json([
                        'status' => 'error',
                        'message' => $responseBody['metadata']['message'] ?? 'Permintaan BPJS gagal',
                        'response_time' => number_format($responseTime, 2)
                    ], 400);
                }

                if (!isset($responseBody['response'])) {
                    Log::warning("BPJS response tidak mengandung 'response'", ['responseBody' => $responseBody]);
                    continue;
                }

                $encryptedString = $responseBody['response'];
                $encrypt_method = 'AES-256-CBC';
                $key_hash = hash('sha256', $key, true);
                $iv = substr($key_hash, 0, 16);

                for ($decAttempt = 0; $decAttempt < $maxDecryptRetries; $decAttempt++) {
                    $decryptedString = openssl_decrypt(
                        base64_decode($encryptedString),
                        $encrypt_method,
                        $key_hash,
                        OPENSSL_RAW_DATA,
                        $iv
                    );

                    if ($decryptedString) {
                        $jsonString = LZString::decompressFromEncodedURIComponent($decryptedString);

                        if ($jsonString) {
                            $data = json_decode($jsonString, true);
                            if ($data !== null) {
                                break 2; // Berhasil, keluar dari kedua loop
                            }
                        }
                    }

                    Log::warning("Dekripsi ke-{$decAttempt} gagal");
                }

                // Fallback jika 3x dekripsi gagal
                Log::warning("Dekripsi gagal total, fallback ke bpjs_dekrip_internal");
                $fallback = $this->bpjs_dekrip_internal($timestamp, $encryptedString);

                if (isset($fallback['data'])) {
                    $data = $fallback['data'];
                    break;
                }
            } catch (\Exception $e) {
                Log::error("Error: " . $e->getMessage());
                if ($reqAttempt >= $maxRequestRetries - 1) {
                    return response()->json([
                        'status' => 'error',
                        'message' => $e->getMessage(),
                        'response_time' => number_format($responseTime, 2)
                    ], 400);
                }
            }
        }

        if (empty($data) || !isset($data['list']) || empty($data['list'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'No data found',
                'response_time' => number_format($responseTime, 2)
            ], 400);
        }

        return response()->json([
            'data' => $data,
            'response_time' => number_format($responseTime, 2)
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

        $maxRequestRetries = 3;
        $maxDecryptRetries = 3;
        $responseTime = 0;

        for ($reqAttempt = 0; $reqAttempt < $maxRequestRetries; $reqAttempt++) {
            try {
                $startTime = microtime(true);

                $token = $this->get_token();
                $headers = array_merge([
                    'Content-Type' => 'application/json; charset=utf-8'
                ], $token['headers']);
                $timestamp = $token['headers']['X-Timestamp'];

                $url = "{$BASE_URL}/{$SERVICE_NAME}/{$feature}/{$params1}/{$params2}";
                $response = Http::withHeaders($headers)->get($url);
                $endTime = microtime(true);
                $responseTime = $endTime - $startTime;

                Log::info("Request BPJS", ['url' => $url, 'headers' => $headers]);
                Log::info("BPJS Response", ['body' => $response->body()]);

                $responseBody = json_decode($response->body(), true);

                // Cek jika tidak JSON atau gagal decode
                if (!is_array($responseBody)) {
                    continue; // lanjut request ulang
                }

                // Cek metadata gagal
                if (isset($responseBody['metadata']) && $responseBody['metadata']['code'] != 200) {
                    return response()->json([
                        'status' => 'error',
                        'message' => $responseBody['metadata']['message'] ?? 'Permintaan gagal dari BPJS',
                        'response_time' => number_format($responseTime, 2)
                    ], 400);
                }

                if (!isset($responseBody['response'])) {
                    Log::warning("BPJS response tidak mengandung 'response' terenkripsi", ['responseBody' => $responseBody]);
                    continue;
                }

                $encryptedString = $responseBody['response'];

                // Coba dekripsi maksimal 3x
                for ($decAttempt = 0; $decAttempt < $maxDecryptRetries; $decAttempt++) {
                    $decrypted = $this->bpjs_dekrip_internal($timestamp, $encryptedString);

                    if (isset($decrypted['data']) && !empty($decrypted['data']['list'])) {
                        return response()->json([
                            'data' => $decrypted['data'],
                            'response_time' => number_format($responseTime, 2)
                        ]);
                    }
                }
            } catch (\Exception $e) {
                Log::error("Exception BPJS provider", ['message' => $e->getMessage()]);
                continue;
            }
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Gagal memproses data dari BPJS setelah beberapa kali percobaan.',
            'response_time' => number_format($responseTime, 2)
        ], 400);
    }


    public function get_khusus_bpjs()
    {
        $config = set_bpjs::find(1);
        $BASE_URL = $config->BASE_URL;
        $SERVICE_NAME = $config->SERVICE;
        $feature = 'spesialis/khusus';
        $maxRequestRetries = 3;
        $maxDecryptRetries = 3;
        $data = null;
        $responseTime = 0;

        for ($reqAttempt = 0; $reqAttempt < $maxRequestRetries; $reqAttempt++) {
            try {
                $startTime = microtime(true);

                $token = $this->get_token();
                $headers = array_merge([
                    'Content-Type' => 'application/json; charset=utf-8'
                ], $token['headers']);
                $timestamp = $token['headers']['X-Timestamp'];
                $key = $token['key_decrypt'];

                $response = Http::withHeaders($headers)
                    ->get("{$BASE_URL}/{$SERVICE_NAME}/{$feature}");

                $responseBody = json_decode($response->body(), true);
                $responseTime = microtime(true) - $startTime;

                if (!is_array($responseBody)) {
                    Log::warning("BPJS response bukan array", ['body' => $response->body()]);
                    continue;
                }

                if (isset($responseBody['metadata']) && $responseBody['metadata']['code'] != 200) {
                    return response()->json([
                        'status' => 'error',
                        'message' => $responseBody['metadata']['message'] ?? 'Permintaan BPJS gagal',
                        'response_time' => number_format($responseTime, 2)
                    ], 400);
                }

                if (!isset($responseBody['response'])) {
                    Log::warning("BPJS response tidak mengandung 'response'", ['responseBody' => $responseBody]);
                    continue;
                }

                $encryptedString = $responseBody['response'];
                $encrypt_method = 'AES-256-CBC';
                $key_hash = hash('sha256', $key, true);
                $iv = substr($key_hash, 0, 16);

                for ($decAttempt = 0; $decAttempt < $maxDecryptRetries; $decAttempt++) {
                    $decryptedString = openssl_decrypt(
                        base64_decode($encryptedString),
                        $encrypt_method,
                        $key_hash,
                        OPENSSL_RAW_DATA,
                        $iv
                    );

                    if ($decryptedString) {
                        $jsonString = LZString::decompressFromEncodedURIComponent($decryptedString);

                        if ($jsonString) {
                            $data = json_decode($jsonString, true);
                            if ($data !== null) {
                                break 2; // sukses, keluar dari loop request dan dekripsi
                            }
                        }
                    }

                    Log::warning("Dekripsi ke-{$decAttempt} gagal");
                }

                // Fallback jika semua dekripsi gagal
                Log::warning("Fallback ke bpjs_dekrip_internal");
                $fallback = $this->bpjs_dekrip_internal($timestamp, $encryptedString);
                if (isset($fallback['data'])) {
                    $data = $fallback['data'];
                    break;
                }
            } catch (\Exception $e) {
                Log::error("Error: " . $e->getMessage());
                if ($reqAttempt >= $maxRequestRetries - 1) {
                    return response()->json([
                        'status' => 'error',
                        'message' => $e->getMessage(),
                        'response_time' => number_format($responseTime, 2)
                    ], 400);
                }
            }
        }

        if (empty($data) || !isset($data['list']) || empty($data['list'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'No data found',
                'response_time' => number_format($responseTime, 2)
            ], 400);
        }

        return response()->json([
            'data' => $data,
            'response_time' => number_format($responseTime, 2)
        ]);
    }


    public function get_dphoobat_bpjs($nama)
    {
        $config = set_bpjs::find(1);
        if (!$config) {
            return response()->json(['status' => 'error', 'message' => 'Config not found'], 500);
        }

        $BASE_URL = $config->BASE_URL;
        $SERVICE_NAME = $config->SERVICE;
        $feature = "obat/dpho/{$nama}/1/50";

        $maxRequest = 3;
        $maxDecrypt = 3;
        $responseTime = 0;
        $data = null;

        try {
            $token = $this->get_token();
            if (!$token || !isset($token['headers'], $token['key_decrypt'])) {
                return response()->json(['status' => 'error', 'message' => 'Token retrieval failed'], 500);
            }

            $headers = array_merge([
                'Content-Type' => 'application/json; charset=utf-8'
            ], $token['headers']);
            $timestamp = $token['headers']['X-Timestamp'];
            $key = $token['key_decrypt'];

            for ($i = 0; $i < $maxRequest; $i++) {
                $startTime = microtime(true);
                $response = Http::withHeaders($headers)->get("{$BASE_URL}/{$SERVICE_NAME}/{$feature}");
                $responseTime = microtime(true) - $startTime;

                $responseBody = json_decode($response->body(), true);
                if (!is_array($responseBody)) {
                    continue;
                }

                if (isset($responseBody['metadata']) && $responseBody['metadata']['code'] != 200) {
                    return response()->json([
                        'status' => 'error',
                        'message' => $responseBody['metadata']['message'] ?? 'BPJS request failed',
                        'response_time' => number_format($responseTime, 2)
                    ], 400);
                }

                if (!isset($responseBody['response'])) {
                    continue;
                }

                $encryptedString = $responseBody['response'];
                $encrypt_method = 'AES-256-CBC';
                $key_hash = hash('sha256', $key, true);
                $iv = substr($key_hash, 0, 16);

                for ($j = 0; $j < $maxDecrypt; $j++) {
                    $decryptedString = openssl_decrypt(
                        base64_decode($encryptedString),
                        $encrypt_method,
                        $key_hash,
                        OPENSSL_RAW_DATA,
                        $iv
                    );

                    if ($decryptedString) {
                        $jsonString = LZString::decompressFromEncodedURIComponent($decryptedString);
                        if ($jsonString) {
                            $data = json_decode($jsonString, true);
                            if ($data !== null) {
                                break 2; // Berhasil, keluar dari kedua loop
                            }
                        }
                    }

                    Log::warning("Dekripsi DPHO gagal attempt {$j} (request {$i})");
                }

                // fallback ke internal dekrip
                Log::warning("Fallback dekrip internal - DPHO (request {$i})");
                $fallback = $this->bpjs_dekrip_internal($timestamp, $encryptedString);
                if (isset($fallback['data'])) {
                    $data = $fallback['data'];
                    break;
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'response_time' => number_format($responseTime, 2)
            ], 400);
        }

        if (empty($data) || !isset($data['list']) || empty($data['list'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'No data found',
                'response_time' => number_format($responseTime, 2)
            ], 400);
        }

        return response()->json([
            'status' => 'success',
            'data' => $data,
            'response_time' => number_format($responseTime, 2)
        ]);
    }

    public function get_prognosa_bpjs()
    {
        $config = set_bpjs::find(1);
        $BASE_URL = $config->BASE_URL;
        $SERVICE_NAME = $config->SERVICE;
        $feature = 'prognosa';
        $maxRequestRetries = 3;
        $maxDecryptRetries = 3;
        $data = null;
        $responseTime = 0;

        for ($reqAttempt = 0; $reqAttempt < $maxRequestRetries; $reqAttempt++) {
            try {
                $startTime = microtime(true);

                $token = $this->get_token();
                $headers = array_merge([
                    'Content-Type' => 'application/json; charset=utf-8'
                ], $token['headers']);
                $timestamp = $token['headers']['X-Timestamp'];
                $key = $token['key_decrypt'];

                $response = Http::withHeaders($headers)
                    ->get("{$BASE_URL}/{$SERVICE_NAME}/{$feature}");

                $responseBody = json_decode($response->body(), true);
                $responseTime = microtime(true) - $startTime;

                if (!is_array($responseBody)) {
                    Log::warning("BPJS response bukan array", ['body' => $response->body()]);
                    continue;
                }

                if (isset($responseBody['metadata']) && $responseBody['metadata']['code'] != 200) {
                    return response()->json([
                        'status' => 'error',
                        'message' => $responseBody['metadata']['message'] ?? 'Permintaan BPJS gagal',
                        'response_time' => number_format($responseTime, 2)
                    ], 400);
                }

                if (!isset($responseBody['response'])) {
                    Log::warning("BPJS response tidak mengandung 'response'", ['responseBody' => $responseBody]);
                    continue;
                }

                $encryptedString = $responseBody['response'];
                $encrypt_method = 'AES-256-CBC';
                $key_hash = hash('sha256', $key, true);
                $iv = substr($key_hash, 0, 16);

                for ($decAttempt = 0; $decAttempt < $maxDecryptRetries; $decAttempt++) {
                    $decryptedString = openssl_decrypt(
                        base64_decode($encryptedString),
                        $encrypt_method,
                        $key_hash,
                        OPENSSL_RAW_DATA,
                        $iv
                    );

                    if ($decryptedString) {
                        $jsonString = LZString::decompressFromEncodedURIComponent($decryptedString);

                        if ($jsonString) {
                            $data = json_decode($jsonString, true);
                            if ($data !== null) {
                                break 2; // Berhasil, keluar dari kedua loop
                            }
                        }
                    }

                    Log::warning("Dekripsi ke-{$decAttempt} gagal");
                }

                // Fallback jika dekripsi gagal
                Log::warning("Fallback ke bpjs_dekrip_internal");
                $fallback = $this->bpjs_dekrip_internal($timestamp, $encryptedString);
                if (isset($fallback['data'])) {
                    $data = $fallback['data'];
                    break;
                }
            } catch (\Exception $e) {
                Log::error("Error: " . $e->getMessage());
                if ($reqAttempt >= $maxRequestRetries - 1) {
                    return response()->json([
                        'status' => 'error',
                        'message' => $e->getMessage(),
                        'response_time' => number_format($responseTime, 2)
                    ], 400);
                }
            }
        }

        if (empty($data) || !isset($data['list']) || empty($data['list'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'No data found',
                'response_time' => number_format($responseTime, 2)
            ], 400);
        }

        return response()->json([
            'data' => $data,
            'response_time' => number_format($responseTime, 2)
        ]);
    }

    public function get_alergi_bpjs($kode)
    {
        $config = set_bpjs::find(1);
        if (!$config) {
            return response()->json(['status' => 'error', 'message' => 'Config not found'], 500);
        }

        $BASE_URL = $config->BASE_URL;
        $SERVICE_NAME = $config->SERVICE;
        $feature = "alergi/jenis/{$kode}";

        $maxRequest = 3;
        $maxDecrypt = 3;
        $responseTime = 0;
        $data = null;

        try {
            $token = $this->get_token();
            if (!$token || !isset($token['headers'], $token['key_decrypt'])) {
                return response()->json(['status' => 'error', 'message' => 'Token retrieval failed'], 500);
            }

            $headers = array_merge([
                'Content-Type' => 'application/json; charset=utf-8'
            ], $token['headers']);
            $timestamp = $token['headers']['X-Timestamp'];
            $key = $token['key_decrypt'];

            for ($i = 0; $i < $maxRequest; $i++) {
                $startTime = microtime(true);
                $response = Http::withHeaders($headers)->get("{$BASE_URL}/{$SERVICE_NAME}/{$feature}");
                $responseTime = microtime(true) - $startTime;

                $responseBody = json_decode($response->body(), true);
                if (!is_array($responseBody)) {
                    continue;
                }

                if (isset($responseBody['metadata']) && $responseBody['metadata']['code'] != 200) {
                    return response()->json([
                        'status' => 'error',
                        'message' => $responseBody['metadata']['message'] ?? 'BPJS request failed',
                        'response_time' => number_format($responseTime, 2)
                    ], 400);
                }

                if (!isset($responseBody['response'])) {
                    continue;
                }

                $encryptedString = $responseBody['response'];
                $encrypt_method = 'AES-256-CBC';
                $key_hash = hash('sha256', $key, true);
                $iv = substr($key_hash, 0, 16);

                for ($j = 0; $j < $maxDecrypt; $j++) {
                    $decryptedString = openssl_decrypt(
                        base64_decode($encryptedString),
                        $encrypt_method,
                        $key_hash,
                        OPENSSL_RAW_DATA,
                        $iv
                    );

                    if ($decryptedString) {
                        $jsonString = LZString::decompressFromEncodedURIComponent($decryptedString);
                        if ($jsonString) {
                            $data = json_decode($jsonString, true);
                            if ($data !== null) {
                                break 2; // keluar dari kedua loop
                            }
                        }
                    }

                    Log::warning("Dekripsi alergi gagal attempt {$j} (request {$i})");
                }

                // fallback ke internal dekrip
                Log::warning("Fallback dekrip internal - alergi (request {$i})");
                $fallback = $this->bpjs_dekrip_internal($timestamp, $encryptedString);
                if (isset($fallback['data'])) {
                    $data = $fallback['data'];
                    break;
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'response_time' => number_format($responseTime, 2)
            ], 400);
        }

        if (empty($data) || !isset($data['list']) || empty($data['list'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'No data found',
                'response_time' => number_format($responseTime, 2)
            ], 400);
        }

        return response()->json([
            'status' => 'success',
            'data' => $data,
            'response_time' => number_format($responseTime, 2)
        ]);
    }


    public function get_ws_poli_bpjs()
    {
        $config = set_bpjs::find(1);
        if (!$config) {
            return response()->json(['status' => 'error', 'message' => 'Config not found'], 500);
        }

        $BASE_URL = $config->BASE_URL;
        $SERVICE_NAME = $config->SERVICE_ANTREAN;
        $feature = 'ref/poli/tanggal';
        $params = date('Y-m-d');

        $maxDecryptRetries = 3;
        $data = null;
        $responseTime = 0;

        try {
            $startTime = microtime(true);

            $token = $this->get_token();
            if (!$token || !isset($token['headers'], $token['key_decrypt'])) {
                return response()->json(['status' => 'error', 'message' => 'Token retrieval failed'], 500);
            }

            $headers = array_merge([
                'Content-Type' => 'application/json; charset=utf-8'
            ], $token['headers']);
            $timestamp = $token['headers']['X-Timestamp'];
            $key = $token['key_decrypt'];

            $response = Http::withHeaders($headers)
                ->get("{$BASE_URL}/{$SERVICE_NAME}/{$feature}/{$params}");

            $responseTime = microtime(true) - $startTime;

            $responseBody = json_decode($response->body(), true);

            if (!is_array($responseBody)) {
                return response()->json(['status' => 'error', 'message' => 'Invalid response format', 'response_time' => number_format($responseTime, 2)], 400);
            }

            if (isset($responseBody['metadata']) && $responseBody['metadata']['code'] != 200) {
                return response()->json([
                    'status' => 'error',
                    'message' => $responseBody['metadata']['message'] ?? 'BPJS request failed',
                    'response_time' => number_format($responseTime, 2)
                ], 400);
            }

            if (!isset($responseBody['response'])) {
                return response()->json(['status' => 'error', 'message' => 'Field "response" not found', 'response_time' => number_format($responseTime, 2)], 400);
            }

            $encryptedString = $responseBody['response'];
            $encrypt_method = 'AES-256-CBC';
            $key_hash = hash('sha256', $key, true);
            $iv = substr($key_hash, 0, 16);

            for ($decAttempt = 0; $decAttempt < $maxDecryptRetries; $decAttempt++) {
                $decryptedString = openssl_decrypt(
                    base64_decode($encryptedString),
                    $encrypt_method,
                    $key_hash,
                    OPENSSL_RAW_DATA,
                    $iv
                );

                if ($decryptedString) {
                    $jsonString = LZString::decompressFromEncodedURIComponent($decryptedString);
                    if ($jsonString) {
                        $data = json_decode($jsonString, true);
                        if ($data !== null) {
                            break;
                        }
                    }
                }

                Log::warning("Dekripsi WS Poli gagal percobaan ke-{$decAttempt}");
            }

            // Fallback jika dekripsi gagal
            if ($data === null) {
                Log::warning("Fallback ke bpjs_dekrip_internal");
                $fallback = $this->bpjs_dekrip_internal($timestamp, $encryptedString);
                if (isset($fallback['data'])) {
                    $data = $fallback['data'];
                }
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 400);
        }

        if (empty($data)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan atau kosong',
                'response_time' => number_format($responseTime, 2)
            ], 400);
        }

        return response()->json([
            'status' => 'success',
            'data' => $data,
            'response_time' => number_format($responseTime, 2)
        ]);
    }


    public function get_ws_dokter_bpjs($kode)
    {
        $config = set_bpjs::find(1);
        if (!$config) {
            return response()->json(['status' => 'error', 'message' => 'Config not found'], 500);
        }

        $BASE_URL = $config->BASE_URL;
        $SERVICE_NAME = $config->SERVICE_ANTREAN;
        $tanggal = date('Y-m-d');
        $endpoint = "ref/dokter/kodepoli/{$kode}/tanggal/{$tanggal}";

        $maxRequest = 3;
        $maxDecrypt = 3;
        $responseTime = 0;
        $data = null;

        try {
            $token = $this->get_token();
            if (!$token || !isset($token['headers'], $token['key_decrypt'])) {
                return response()->json(['status' => 'error', 'message' => 'Token retrieval failed'], 500);
            }

            $headers = array_merge([
                'Content-Type' => 'application/json; charset=utf-8'
            ], $token['headers']);
            $timestamp = $token['headers']['X-Timestamp'];
            $key = $token['key_decrypt'];

            for ($i = 0; $i < $maxRequest; $i++) {
                $startTime = microtime(true);
                $response = Http::withHeaders($headers)->get("{$BASE_URL}/{$SERVICE_NAME}/{$endpoint}");
                $responseTime = microtime(true) - $startTime;

                $responseBody = json_decode($response->body(), true);
                if (!is_array($responseBody)) continue;

                if (isset($responseBody['metadata']) && $responseBody['metadata']['code'] != 200) {
                    return response()->json([
                        'status' => 'error',
                        'message' => $responseBody['metadata']['message'] ?? 'BPJS error',
                        'response_time' => number_format($responseTime, 2)
                    ], 400);
                }

                if (!isset($responseBody['response'])) continue;

                $encryptedString = $responseBody['response'];
                $encrypt_method = 'AES-256-CBC';
                $key_hash = hash('sha256', $key, true);
                $iv = substr($key_hash, 0, 16);

                for ($j = 0; $j < $maxDecrypt; $j++) {
                    $decryptedString = openssl_decrypt(
                        base64_decode($encryptedString),
                        $encrypt_method,
                        $key_hash,
                        OPENSSL_RAW_DATA,
                        $iv
                    );

                    if ($decryptedString) {
                        $jsonString = LZString::decompressFromEncodedURIComponent($decryptedString);
                        if ($jsonString) {
                            $data = json_decode($jsonString, true);
                            if ($data !== null) {
                                break 2;
                            }
                        }
                    }

                    Log::warning("Dekripsi gagal attempt {$j} (request {$i})");
                }

                // Fallback dekrip internal
                $fallback = $this->bpjs_dekrip_internal($timestamp, $encryptedString);
                if (isset($fallback['data'])) {
                    $data = $fallback['data'];
                    break;
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'response_time' => number_format($responseTime, 2)
            ], 400);
        }

        if (empty($data) || !isset($data['list']) || empty($data['list'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'No data found',
                'response_time' => number_format($responseTime, 2)
            ], 400);
        }

        return response()->json([
            'status' => 'success',
            'data' => $data,
            'response_time' => number_format($responseTime, 2)
        ]);
    }


    public function post_ws_antria_bpjs($data)
    {
        $config = set_bpjs::find(1);
        $BASE_URL = $config->BASE_URL;
        $SERVICE_NAME = $config->SERVICE_ANTREAN;
        $feature = 'antrean/add';
        $token = $this->get_token();
        try {
            // Assuming $this->generateHeaders() returns an array of headers
            $headers = array_merge([
                'Content-Type' => 'application/json; charset=utf-8'
            ], $token['headers']);

            // Make the API request
            $response = Http::withHeaders($headers)
                ->post("{$BASE_URL}/{$SERVICE_NAME}/{$feature}", $data);

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
        $key = $token['key_decrypt'];
        $encrypt_method = 'AES-256-CBC';
        $key_hash = hex2bin(hash('sha256', $key));  // Get key hash
        $iv = substr(hex2bin(hash('sha256', $key)), 0, 16);  // Get IV

        // Decrypt the base64-encoded encrypted string
        $decryptedString = openssl_decrypt(base64_decode($encryptedString), $encrypt_method, $key_hash, OPENSSL_RAW_DATA, $iv);

        $jsonString = LZString::decompressFromEncodedURIComponent($decryptedString);

        // Decompress the string
        $data = json_decode($jsonString, true);


        return response()->json($data);
    }

    public function delete_ws_antria_bpjs($data)
    {
        $config = set_bpjs::find(1);
        $BASE_URL = $config->BASE_URL;
        $SERVICE_NAME = $config->SERVICE_ANTREAN;
        $feature = 'antrean/batal';
        $token = $this->get_token();

        try {
            // Assuming $this->generateHeaders() returns an array of headers
            $headers = array_merge([
                'Content-Type' => 'application/json; charset=utf-8'
            ], $token['headers']);

            // Make the API request
            $response = Http::withHeaders($headers)
                ->post("{$BASE_URL}/{$SERVICE_NAME}/{$feature}", $data);

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
        $key = $token['key_decrypt'];
        $encrypt_method = 'AES-256-CBC';
        $key_hash = hex2bin(hash('sha256', $key));  // Get key hash
        $iv = substr(hex2bin(hash('sha256', $key)), 0, 16);  // Get IV

        // Decrypt the base64-encoded encrypted string
        $decryptedString = openssl_decrypt(base64_decode($encryptedString), $encrypt_method, $key_hash, OPENSSL_RAW_DATA, $iv);

        $jsonString = LZString::decompressFromEncodedURIComponent($decryptedString);

        // Decompress the string
        $data = json_decode($jsonString, true);


        return response()->json($data);
    }

    public function update_ws_antria_bpjs($data)
    {
        $config = set_bpjs::find(1);
        $BASE_URL = $config->BASE_URL;
        $SERVICE_NAME = $config->SERVICE_ANTREAN;
        $feature = 'antrean/panggil';
        $token = $this->get_token();
        try {
            // Assuming $this->generateHeaders() returns an array of headers
            $headers = array_merge([
                'Content-Type' => 'application/json; charset=utf-8'
            ], $token['headers']);

            // Make the API request
            $response = Http::withHeaders($headers)
                ->post("{$BASE_URL}/{$SERVICE_NAME}/{$feature}", $data);

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
        $key = $token['key_decrypt'];
        $encrypt_method = 'AES-256-CBC';
        $key_hash = hex2bin(hash('sha256', $key));  // Get key hash
        $iv = substr(hex2bin(hash('sha256', $key)), 0, 16);  // Get IV

        // Decrypt the base64-encoded encrypted string
        $decryptedString = openssl_decrypt(base64_decode($encryptedString), $encrypt_method, $key_hash, OPENSSL_RAW_DATA, $iv);

        $jsonString = LZString::decompressFromEncodedURIComponent($decryptedString);

        // Decompress the string
        $data = json_decode($jsonString, true);


        return response()->json($data);
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
        $tokenData = $this->get_token();

        try {
            $startTime = microtime(true);
            $headers = array_merge([
                'Content-Type' => 'text/plain; charset=utf-8'
            ], $tokenData['headers']);

            $response = Http::withHeaders($headers)
                ->post("{$BASE_URL}/{$SERVICE_NAME}/{$feature}", $datapost);

            // Decode response
            $responseBody = json_decode($response->body(), true);
            $endTime = microtime(true);
            $responseTime = $endTime - $startTime;

            // Fetch the encrypted response data
            $encryptedString = $responseBody['response'];

            // Decrypt the string using AES-256-CBC
            $key = $tokenData['key_decrypt'];
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
                OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, // Bisa coba tambahkan | OPENSSL_ZERO_PADDING jika masih gagal
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
        $maxRequestRetries = 3;
        $maxDecryptRetries = 3;
        $data = null;
        $responseTime = 0;

        for ($reqAttempt = 0; $reqAttempt < $maxRequestRetries; $reqAttempt++) {
            try {
                $startTime = microtime(true);

                $token = $this->get_token();
                $headers = array_merge([
                    'Content-Type' => 'application/json; charset=utf-8'
                ], $token['headers']);
                $timestamp = $token['headers']['X-Timestamp'];
                $key = $token['key_decrypt'];

                $response = Http::withHeaders($headers)
                    ->get("{$BASE_URL}/{$SERVICE_NAME}/{$feature}");

                $responseBody = json_decode($response->body(), true);
                $responseTime = microtime(true) - $startTime;

                if (!is_array($responseBody)) {
                    Log::warning("BPJS response bukan array", ['body' => $response->body()]);
                    continue;
                }

                if (isset($responseBody['metadata']) && $responseBody['metadata']['code'] != 200) {
                    return response()->json([
                        'status' => 'error',
                        'message' => $responseBody['metadata']['message'] ?? 'Permintaan BPJS gagal',
                        'response_time' => number_format($responseTime, 2)
                    ], 400);
                }

                if (!isset($responseBody['response'])) {
                    Log::warning("BPJS response tidak mengandung 'response'", ['responseBody' => $responseBody]);
                    continue;
                }

                $encryptedString = $responseBody['response'];
                $encrypt_method = 'AES-256-CBC';
                $key_hash = hash('sha256', $key, true);
                $iv = substr($key_hash, 0, 16);

                for ($decAttempt = 0; $decAttempt < $maxDecryptRetries; $decAttempt++) {
                    $decryptedString = openssl_decrypt(
                        base64_decode($encryptedString),
                        $encrypt_method,
                        $key_hash,
                        OPENSSL_RAW_DATA,
                        $iv
                    );

                    if ($decryptedString) {
                        $jsonString = LZString::decompressFromEncodedURIComponent($decryptedString);
                        if ($jsonString) {
                            $data = json_decode($jsonString, true);
                            if ($data !== null) {
                                break 2; // Sukses keluar dari kedua loop
                            }
                        }
                    }

                    Log::warning("Dekripsi ke-{$decAttempt} gagal");
                }

                // Fallback jika dekripsi gagal
                Log::warning("Fallback ke bpjs_dekrip_internal");
                $fallback = $this->bpjs_dekrip_internal($timestamp, $encryptedString);
                if (isset($fallback['data'])) {
                    $data = $fallback['data'];
                    break;
                }
            } catch (\Exception $e) {
                Log::error("Error: " . $e->getMessage());
                if ($reqAttempt >= $maxRequestRetries - 1) {
                    return response()->json([
                        'status' => 'error',
                        'message' => $e->getMessage(),
                        'response_time' => number_format($responseTime, 2)
                    ], 400);
                }
            }
        }

        if (empty($data) || !isset($data['list']) || empty($data['list'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'No data found',
                'response_time' => number_format($responseTime, 2)
            ], 400);
        }

        return response()->json([
            'data' => $data,
            'response_time' => number_format($responseTime, 2)
        ]);
    }


    public function get_rujukan_spesialis_bpjs($spesialis, $sarana, $tanggal)
    {
        $config = set_bpjs::find(1);
        if (!$config) {
            return response()->json(['status' => 'error', 'message' => 'Config not found'], 500);
        }

        $BASE_URL = $config->BASE_URL;
        $SERVICE_NAME = $config->SERVICE;
        $endpoint = "spesialis/rujuk/subspesialis/{$spesialis}/sarana/{$sarana}/tglEstRujuk/{$tanggal}";

        $maxRequest = 3;
        $maxDecrypt = 3;
        $responseTime = 0;
        $data = null;

        try {
            $token = $this->get_token();
            if (!$token || !isset($token['headers'], $token['key_decrypt'])) {
                return response()->json(['status' => 'error', 'message' => 'Token retrieval failed'], 500);
            }

            $headers = array_merge([
                'Content-Type' => 'application/json; charset=utf-8'
            ], $token['headers']);
            $timestamp = $token['headers']['X-Timestamp'];
            $key = $token['key_decrypt'];

            for ($i = 0; $i < $maxRequest; $i++) {
                $startTime = microtime(true);
                $response = Http::withHeaders($headers)->get("{$BASE_URL}/{$SERVICE_NAME}/{$endpoint}");
                $responseTime = microtime(true) - $startTime;

                $responseBody = json_decode($response->body(), true);
                if (!is_array($responseBody)) {
                    continue;
                }

                // Tangani metadata error (langsung return)
                if (isset($responseBody['metadata']) && $responseBody['metadata']['code'] != 200) {
                    return response()->json([
                        'status' => 'error',
                        'message' => $responseBody['metadata']['message'] ?? 'BPJS request failed',
                        'response_time' => number_format($responseTime, 2)
                    ], 400);
                }

                if (!isset($responseBody['response'])) {
                    continue;
                }

                $encryptedString = $responseBody['response'];
                $encrypt_method = 'AES-256-CBC';
                $key_hash = hash('sha256', $key, true);
                $iv = substr($key_hash, 0, 16);

                for ($j = 0; $j < $maxDecrypt; $j++) {
                    $decryptedString = openssl_decrypt(
                        base64_decode($encryptedString),
                        $encrypt_method,
                        $key_hash,
                        OPENSSL_RAW_DATA,
                        $iv
                    );

                    if ($decryptedString) {
                        $jsonString = LZString::decompressFromEncodedURIComponent($decryptedString);
                        if ($jsonString) {
                            $data = json_decode($jsonString, true);
                            if ($data !== null) {
                                break 2;
                            }
                        }
                    }

                    Log::warning("Dekripsi rujukan spesialis gagal attempt {$j} (request {$i})");
                }

                // fallback dekrip internal jika tersedia
                Log::warning("Fallback dekrip internal - Rujukan Spesialis (request {$i})");
                $fallback = $this->bpjs_dekrip_internal($timestamp, $encryptedString);
                if (isset($fallback['data'])) {
                    $data = $fallback['data'];
                    break;
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'response_time' => number_format($responseTime, 2)
            ], 400);
        }

        if (empty($data) || !isset($data['list']) || empty($data['list'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'No data found',
                'response_time' => number_format($responseTime, 2)
            ], 400);
        }

        return response()->json([
            'status' => 'success',
            'data' => $data,
            'response_time' => number_format($responseTime, 2)
        ]);
    }


    public function get_rujukan_husus_bpjs($spesialis, $noKartu, $tanggal)
    {
        $config = set_bpjs::find(1);
        if (!$config) {
            return response()->json(['status' => 'error', 'message' => 'Config not found'], 500);
        }

        $BASE_URL = $config->BASE_URL;
        $SERVICE_NAME = $config->SERVICE;
        $endpoint = "spesialis/rujuk/khusus/{$spesialis}/noKartu/{$noKartu}/tglEstRujuk/{$tanggal}";

        $maxRequest = 3;
        $maxDecrypt = 3;
        $responseTime = 0;
        $data = null;

        try {
            $token = $this->get_token();
            if (!$token || !isset($token['headers'], $token['key_decrypt'])) {
                return response()->json(['status' => 'error', 'message' => 'Token retrieval failed'], 500);
            }

            $headers = array_merge([
                'Content-Type' => 'application/json; charset=utf-8'
            ], $token['headers']);
            $timestamp = $token['headers']['X-Timestamp'];
            $key = $token['key_decrypt'];

            for ($i = 0; $i < $maxRequest; $i++) {
                $startTime = microtime(true);
                $response = Http::withHeaders($headers)->get("{$BASE_URL}/{$SERVICE_NAME}/{$endpoint}");
                $responseTime = microtime(true) - $startTime;

                $responseBody = json_decode($response->body(), true);
                if (!is_array($responseBody)) {
                    continue;
                }

                // Handle metadata jika error
                if (isset($responseBody['metadata']) && $responseBody['metadata']['code'] != 200) {
                    return response()->json([
                        'status' => 'error',
                        'message' => $responseBody['metadata']['message'] ?? 'BPJS error',
                        'response_time' => number_format($responseTime, 2)
                    ], 400);
                }

                if (!isset($responseBody['response'])) {
                    continue;
                }

                $encryptedString = $responseBody['response'];
                $encrypt_method = 'AES-256-CBC';
                $key_hash = hash('sha256', $key, true);
                $iv = substr($key_hash, 0, 16);

                for ($j = 0; $j < $maxDecrypt; $j++) {
                    $decryptedString = openssl_decrypt(
                        base64_decode($encryptedString),
                        $encrypt_method,
                        $key_hash,
                        OPENSSL_RAW_DATA,
                        $iv
                    );

                    if ($decryptedString) {
                        $jsonString = LZString::decompressFromEncodedURIComponent($decryptedString);
                        if ($jsonString) {
                            $data = json_decode($jsonString, true);
                            if ($data !== null) {
                                break 2; // sukses, keluar dari 2 loop sekaligus
                            }
                        }
                    }

                    Log::warning("Dekripsi gagal attempt {$j} (request {$i})");
                }

                // Coba fallback internal jika ada
                Log::warning("Fallback dekrip internal - Rujukan Khusus (request {$i})");
                $fallback = $this->bpjs_dekrip_internal($timestamp, $encryptedString);
                if (isset($fallback['data'])) {
                    $data = $fallback['data'];
                    break;
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'response_time' => number_format($responseTime, 2)
            ], 400);
        }

        if (empty($data) || !isset($data['list']) || empty($data['list'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'No data found',
                'response_time' => number_format($responseTime, 2)
            ], 400);
        }

        return response()->json([
            'status' => 'success',
            'data' => $data,
            'response_time' => number_format($responseTime, 2)
        ]);
    }


    public function get_rujukan_husus_subspesialis_bpjs($husus, $spesialis, $noKartu, $tanggal)
    {
        $config = set_bpjs::find(1);
        if (!$config) {
            return response()->json(['status' => 'error', 'message' => 'Config not found'], 500);
        }

        $BASE_URL = $config->BASE_URL;
        $SERVICE_NAME = $config->SERVICE;
        $endpoint = "spesialis/rujuk/khusus/{$spesialis}/subspesialis/{$husus}/noKartu/{$noKartu}/tglEstRujuk/{$tanggal}";

        $maxRequest = 3;
        $maxDecrypt = 3;
        $responseTime = 0;
        $data = null;

        try {
            $token = $this->get_token();
            if (!$token || !isset($token['headers'], $token['key_decrypt'])) {
                return response()->json(['status' => 'error', 'message' => 'Token retrieval failed'], 500);
            }

            $headers = array_merge([
                'Content-Type' => 'application/json; charset=utf-8'
            ], $token['headers']);
            $timestamp = $token['headers']['X-Timestamp'];
            $key = $token['key_decrypt'];

            for ($i = 0; $i < $maxRequest; $i++) {
                $startTime = microtime(true);
                $response = Http::withHeaders($headers)->get("{$BASE_URL}/{$SERVICE_NAME}/{$endpoint}");
                $responseTime = microtime(true) - $startTime;

                $responseBody = json_decode($response->body(), true);
                if (!is_array($responseBody)) continue;

                if (isset($responseBody['metadata']) && $responseBody['metadata']['code'] != 200) {
                    return response()->json([
                        'status' => 'error',
                        'message' => $responseBody['metadata']['message'] ?? 'BPJS error',
                        'response_time' => number_format($responseTime, 2)
                    ], 400);
                }

                if (!isset($responseBody['response'])) continue;

                $encryptedString = $responseBody['response'];
                $encrypt_method = 'AES-256-CBC';
                $key_hash = hash('sha256', $key, true);
                $iv = substr($key_hash, 0, 16);

                for ($j = 0; $j < $maxDecrypt; $j++) {
                    $decryptedString = openssl_decrypt(
                        base64_decode($encryptedString),
                        $encrypt_method,
                        $key_hash,
                        OPENSSL_RAW_DATA,
                        $iv
                    );

                    if ($decryptedString) {
                        $jsonString = LZString::decompressFromEncodedURIComponent($decryptedString);
                        if ($jsonString) {
                            $data = json_decode($jsonString, true);
                            if ($data !== null) {
                                break 2;
                            }
                        }
                    }

                    Log::warning("Dekripsi gagal attempt {$j} (request {$i})");
                }

                Log::warning("Fallback dekrip internal - Rujukan Khusus Subspesialis (request {$i})");
                $fallback = $this->bpjs_dekrip_internal($timestamp, $encryptedString);
                if (isset($fallback['data'])) {
                    $data = $fallback['data'];
                    break;
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'response_time' => number_format($responseTime, 2)
            ], 400);
        }

        if (empty($data) || !isset($data['list']) || empty($data['list'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'No data found',
                'response_time' => number_format($responseTime, 2)
            ], 400);
        }

        return response()->json([
            'status' => 'success',
            'data' => $data,
            'response_time' => number_format($responseTime, 2)
        ]);
    }


    public function post_kunjungan_bpjs($data)
    {
        $config = set_bpjs::find(1);
        $BASE_URL = $config->BASE_URL;
        $SERVICE_NAME = $config->SERVICE;
        $feature = 'kunjungan/v1';
        $tokendata = $this->get_token();
        try {
            // Assuming $this->generateHeaders() returns an array of headers
            $headers = array_merge([
                'Content-Type' => 'text/plain; charset=utf-8'
            ], $tokendata['headers']);

            // Make the API request
            $response = Http::withHeaders($headers)
                ->post("{$BASE_URL}/{$SERVICE_NAME}/{$feature}", $data);

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
        $key = $tokendata['key_decrypt'];
        $encrypt_method = 'AES-256-CBC';
        $key_hash = hex2bin(hash('sha256', $key));  // Get key hash
        $iv = substr(hex2bin(hash('sha256', $key)), 0, 16);  // Get IV

        // Decrypt the base64-encoded encrypted string
        $decryptedString = openssl_decrypt(base64_decode($encryptedString), $encrypt_method, $key_hash, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $iv);

        $jsonString = LZString::decompressFromEncodedURIComponent($decryptedString);

        // Decompress the string
        $data = json_decode($jsonString, true);


        return response()->json([
            "data" => $data
        ]);
    }

    public function get_jadwal_dokter_bpjs($kodepoli, $tanggal)
    {
        $config = set_bpjs::find(1);
        $BASE_URL = $config->BASE_URL;
        $SERVICE_NAME = $config->SERVICE_ANTREAN;
        $feature = 'ref/dokter/kodepoli';

        $maxRequestRetries = 3;
        $maxDecryptRetries = 3;
        $responseTime = 0;
        $data = null;

        for ($reqAttempt = 0; $reqAttempt < $maxRequestRetries; $reqAttempt++) {
            try {
                $startTime = microtime(true);

                $token = $this->get_token();
                $headers = array_merge([
                    'Content-Type' => 'application/json; charset=utf-8'
                ], $token['headers']);
                $timestamp = $token['headers']['X-Timestamp'];

                $url = "{$BASE_URL}/{$SERVICE_NAME}/{$feature}/{$kodepoli}/tanggal/{$tanggal}";
                Log::info("Request BPJS", ['url' => $url, 'headers' => $headers]);

                $response = Http::withHeaders($headers)->get($url);
                $responseBody = json_decode($response->body(), true);
                $responseTime = microtime(true) - $startTime;

                if (!is_array($responseBody)) {
                    Log::warning("BPJS response bukan JSON", ['body' => $response->body()]);
                    continue;
                }

                if (isset($responseBody['metadata']) && $responseBody['metadata']['code'] != 200) {
                    return response()->json([
                        'status' => 'error',
                        'message' => $responseBody['metadata']['message'] ?? 'Permintaan BPJS gagal',
                        'response_time' => number_format($responseTime, 2)
                    ], 400);
                }

                if (!isset($responseBody['response'])) {
                    Log::warning("BPJS response tidak mengandung 'response'", ['responseBody' => $responseBody]);
                    continue;
                }

                $encryptedString = $responseBody['response'];
                $key = $token['key_decrypt'];
                $encrypt_method = 'AES-256-CBC';
                $key_hash = hash('sha256', $key, true);
                $iv = substr($key_hash, 0, 16);

                // Coba dekripsi maksimal 3 kali
                for ($decAttempt = 0; $decAttempt < $maxDecryptRetries; $decAttempt++) {
                    $decryptedString = openssl_decrypt(
                        base64_decode($encryptedString),
                        $encrypt_method,
                        $key_hash,
                        OPENSSL_RAW_DATA,
                        $iv
                    );

                    if ($decryptedString) {
                        $jsonString = LZString::decompressFromEncodedURIComponent($decryptedString);

                        if ($jsonString) {
                            $data = json_decode($jsonString, true);
                            if ($data !== null) {
                                break 2; // keluar dari kedua loop (request & dekripsi)
                            }
                        }
                    }

                    Log::warning("Percobaan dekripsi ke-{$decAttempt} gagal");
                }

                // Jika gagal semua percobaan dekripsi, coba fallback
                Log::warning("Gagal dekripsi 3x, coba fallback internal");
                $fallback = $this->bpjs_dekrip_internal($timestamp, $encryptedString);
                if (isset($fallback['data'])) {
                    $data = $fallback['data'];
                    break;
                }
            } catch (\Exception $e) {
                Log::error("Error: " . $e->getMessage());
                if ($reqAttempt >= $maxRequestRetries - 1) {
                    return response()->json([
                        'status' => 'error',
                        'message' => $e->getMessage(),
                        'response_time' => number_format($responseTime, 2)
                    ], 400);
                }
            }
        }

        if (empty($data)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memproses data dari BPJS',
                'response_time' => number_format($responseTime, 2)
            ], 400);
        }

        return response()->json([
            'data' => $data,
            'response_time' => number_format($responseTime, 2)
        ]);
    }

    public function get_pendaftaran_nomor($nomor, $tanggal)
    {
        $config = set_bpjs::find(1);
        if (!$config) {
            return response()->json(['status' => 'error', 'message' => 'Config not found'], 500);
        }

        $BASE_URL = $config->BASE_URL;
        $SERVICE_NAME = $config->SERVICE;
        $endpoint = "pendaftaran/noUrut/{$nomor}/tglDaftar/{$tanggal}";

        $maxRequest = 3;
        $maxDecrypt = 3;
        $responseTime = 0;
        $data = null;

        try {
            $token = $this->get_token();
            if (!$token || !isset($token['headers'], $token['key_decrypt'])) {
                return response()->json(['status' => 'error', 'message' => 'Token retrieval failed'], 500);
            }

            $headers = array_merge([
                'Content-Type' => 'application/json; charset=utf-8'
            ], $token['headers']);
            $timestamp = $token['headers']['X-Timestamp'];
            $key = $token['key_decrypt'];

            for ($i = 0; $i < $maxRequest; $i++) {
                $startTime = microtime(true);
                $response = Http::withHeaders($headers)->get("{$BASE_URL}/{$SERVICE_NAME}/{$endpoint}");
                $responseTime = microtime(true) - $startTime;

                $responseBody = json_decode($response->body(), true);
                if (!is_array($responseBody)) continue;

                if (isset($responseBody['metadata']) && $responseBody['metadata']['code'] != 200) {
                    return response()->json([
                        'status' => 'error',
                        'message' => $responseBody['metadata']['message'] ?? 'BPJS error',
                        'response_time' => number_format($responseTime, 2)
                    ], 400);
                }

                if (!isset($responseBody['response'])) continue;

                $encryptedString = $responseBody['response'];
                $encrypt_method = 'AES-256-CBC';
                $key_hash = hash('sha256', $key, true);
                $iv = substr($key_hash, 0, 16);

                for ($j = 0; $j < $maxDecrypt; $j++) {
                    $decryptedString = openssl_decrypt(
                        base64_decode($encryptedString),
                        $encrypt_method,
                        $key_hash,
                        OPENSSL_RAW_DATA,
                        $iv
                    );

                    if ($decryptedString) {
                        $jsonString = LZString::decompressFromEncodedURIComponent($decryptedString);
                        if ($jsonString) {
                            $data = json_decode($jsonString, true);
                            if ($data !== null) {
                                break 2;
                            }
                        }
                    }

                    Log::warning("Dekripsi gagal attempt {$j} (request {$i})");
                }

                Log::warning("Fallback dekrip internal - Rujukan Khusus Subspesialis (request {$i})");
                $fallback = $this->bpjs_dekrip_internal($timestamp, $encryptedString);
                if (isset($fallback['data'])) {
                    $data = $fallback['data'];
                    break;
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'response_time' => number_format($responseTime, 2)
            ], 400);
        }

        if (empty($data) || !isset($data['list']) || empty($data['list'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'No data found',
                'response_time' => number_format($responseTime, 2)
            ], 400);
        }

        return response()->json([
            'status' => 'success',
            'data' => $data,
            'response_time' => number_format($responseTime, 2)
        ]);
    }

    public function get_pendaftaran_provide($tanggal)
    {
        $config = set_bpjs::find(1);
        if (!$config) {
            return response()->json(['status' => 'error', 'message' => 'Config not found'], 500);
        }

        $BASE_URL = $config->BASE_URL;
        $SERVICE_NAME = $config->SERVICE;
        $endpoint = "pendaftaran/tglDaftar/{$tanggal}/1/10";

        $maxRequest = 3;
        $maxDecrypt = 3;
        $responseTime = 0;
        $data = null;

        try {
            $token = $this->get_token();
            if (!$token || !isset($token['headers'], $token['key_decrypt'])) {
                return response()->json(['status' => 'error', 'message' => 'Token retrieval failed'], 500);
            }

            $headers = array_merge([
                'Content-Type' => 'application/json; charset=utf-8'
            ], $token['headers']);
            $timestamp = $token['headers']['X-Timestamp'];
            $key = $token['key_decrypt'];

            for ($i = 0; $i < $maxRequest; $i++) {
                $startTime = microtime(true);
                $response = Http::withHeaders($headers)->get("{$BASE_URL}/{$SERVICE_NAME}/{$endpoint}");
                $responseTime = microtime(true) - $startTime;

                $responseBody = json_decode($response->body(), true);
                if (!is_array($responseBody)) continue;

                if (isset($responseBody['metadata']) && $responseBody['metadata']['code'] != 200) {
                    return response()->json([
                        'status' => 'error',
                        'message' => $responseBody['metadata']['message'] ?? 'BPJS error',
                        'response_time' => number_format($responseTime, 2)
                    ], 400);
                }

                if (!isset($responseBody['response'])) continue;

                $encryptedString = $responseBody['response'];
                $encrypt_method = 'AES-256-CBC';
                $key_hash = hash('sha256', $key, true);
                $iv = substr($key_hash, 0, 16);

                for ($j = 0; $j < $maxDecrypt; $j++) {
                    $decryptedString = openssl_decrypt(
                        base64_decode($encryptedString),
                        $encrypt_method,
                        $key_hash,
                        OPENSSL_RAW_DATA,
                        $iv
                    );

                    if ($decryptedString) {
                        $jsonString = LZString::decompressFromEncodedURIComponent($decryptedString);
                        if ($jsonString) {
                            $data = json_decode($jsonString, true);
                            if ($data !== null) {
                                break 2;
                            }
                        }
                    }

                    Log::warning("Dekripsi gagal attempt {$j} (request {$i})");
                }

                Log::warning("Fallback dekrip internal - Rujukan Khusus Subspesialis (request {$i})");
                $fallback = $this->bpjs_dekrip_internal($timestamp, $encryptedString);
                if (isset($fallback['data'])) {
                    $data = $fallback['data'];
                    break;
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'response_time' => number_format($responseTime, 2)
            ], 400);
        }

        if (empty($data) || !isset($data['list']) || empty($data['list'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'No data found',
                'response_time' => number_format($responseTime, 2)
            ], 400);
        }

        return response()->json([
            'status' => 'success',
            'data' => $data,
            'response_time' => number_format($responseTime, 2)
        ]);
    }

    public function delete_pendaftaran($data)
    {
        $config = set_bpjs::find(1);
        if (!$config) {
            return response()->json(['status' => 'error', 'message' => 'Config not found'], 500);
        }

        $nomor = $data['nomorkartu'];
        $tanggal = $data['tanggalperiksa'];
        $nomorurut = $data['nourut'];
        $kodepoli = $data['kodepoli'];

        if (!$nomor || !$tanggal || !$nomorurut || !$kodepoli) {
            return response()->json(['status' => 'error', 'message' => 'Invalid input data'], 400);
        }

        $BASE_URL = $config->BASE_URL;
        $SERVICE_NAME = $config->SERVICE;
        $endpoint = "pendaftaran/peserta/{$nomor}/tglDaftar/{$tanggal}/noUrut/{$nomorurut}/kdPoli/{$kodepoli}";

        $maxRequest = 3;
        $maxDecrypt = 3;
        $responseTime = 0;
        $data = null;
        try {
            $token = $this->get_token();
            if (!$token || !isset($token['headers'], $token['key_decrypt'])) {
                return response()->json(['status' => 'error', 'message' => 'Token retrieval failed'], 500);
            }

            $headers = array_merge([
                'Content-Type' => 'application/json; charset=utf-8'
            ], $token['headers']);
            $timestamp = $token['headers']['X-Timestamp'];
            $key = $token['key_decrypt'];

            $data = null;

            for ($i = 0; $i < $maxRequest; $i++) {
                $startTime = microtime(true);
                $response = Http::withHeaders($headers)->delete("{$BASE_URL}/{$SERVICE_NAME}/{$endpoint}");
                $responseTime = microtime(true) - $startTime;

                $responseBody = json_decode($response->body(), true);
                if (!is_array($responseBody)) continue;

                $meta = $responseBody['metaData'] ?? $responseBody['metadata'] ?? null;

                if (!$meta || ($meta['code'] ?? 500) != 200) {
                    return response()->json([
                        'status' => 'error',
                        'message' => $meta['message'] ?? 'BPJS error',
                        'response_time' => number_format($responseTime, 2)
                    ], 400);
                }

                // Jika response null (seperti pada DELETE), anggap berhasil
                if (!isset($responseBody['response']) || $responseBody['response'] === null) {
                    return response()->json([
                        'status' => 'success',
                        'message' => $meta['message'] ?? 'Berhasil',
                        'data' => null,
                        'response_time' => number_format($responseTime, 2)
                    ]);
                }

                // Proses dekripsi jika ada response terenkripsi
                $encryptedString = $responseBody['response'];
                $encrypt_method = 'AES-256-CBC';
                $key_hash = hash('sha256', $key, true);
                $iv = substr($key_hash, 0, 16);

                for ($j = 0; $j < $maxDecrypt; $j++) {
                    $decryptedString = openssl_decrypt(
                        base64_decode($encryptedString),
                        $encrypt_method,
                        $key_hash,
                        OPENSSL_RAW_DATA,
                        $iv
                    );

                    if ($decryptedString) {
                        $jsonString = LZString::decompressFromEncodedURIComponent($decryptedString);
                        if ($jsonString) {
                            $data = json_decode($jsonString, true);
                            if ($data !== null) {
                                break 2; // keluar dari kedua loop (dekripsi & request)
                            }
                        }
                    }

                    Log::warning("Dekripsi gagal attempt {$j} (request {$i})");
                }

                Log::warning("Fallback dekrip internal - Rujukan Khusus Subspesialis (request {$i})");
                $fallback = $this->bpjs_dekrip_internal($timestamp, $encryptedString);
                if (isset($fallback['data'])) {
                    $data = $fallback['data'];
                    break;
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'response_time' => number_format($responseTime ?? 0, 2)
            ], 500);
        }

        // Jika ada data hasil dekripsi atau fallback
        if (!empty($data)) {
            return response()->json([
                'status' => 'success',
                'data' => $data,
                'response_time' => number_format($responseTime ?? 0, 2)
            ]);
        }

        // Jika tidak ada data setelah semua percobaan
        return response()->json([
            'status' => 'error',
            'message' => 'No data found or failed to decrypt.',
            'response_time' => number_format($responseTime ?? 0, 2)
        ], 400);

    }

    public function bpjs_dekrip(Request $request)
    {
        // Ambil timestamp dan data dari request
        $timestamp = $request->input('timestamp');
        $encryptedString = $request->input('data'); // Langsung ambil dari body POST

        // Pastikan data tidak kosong
        if (!$encryptedString) {
            return response()->json(['error' => 'Data terenkripsi tidak boleh kosong'], 400);
        }

        // Ambil konfigurasi BPJS
        $config = set_bpjs::find(1);
        if (!$config) {
            return response()->json(['error' => 'Konfigurasi BPJS tidak ditemukan'], 500);
        }

        $cons_id = $config->CONSID;
        $secret_key = $config->SCREET_KEY;

        // Generate key dan IV untuk dekripsi
        $key = $cons_id . $secret_key . $timestamp;
        $encrypt_method = 'AES-256-CBC';
        $key_hash = hex2bin(hash('sha256', $key));
        $iv = substr($key_hash, 0, 16); // IV harus 16 byte

        // Dekripsi data yang sudah di base64_encode()
        $decryptedString = openssl_decrypt(base64_decode($encryptedString), $encrypt_method, $key_hash, OPENSSL_RAW_DATA, $iv);

        if (!$decryptedString) {
            return response()->json(['error' => 'Gagal mendekripsi data'], 500);
        }

        // Dekompresi data menggunakan LZString
        $jsonString = LZString::decompressFromEncodedURIComponent($decryptedString);

        if (!$jsonString) {
            return response()->json(['error' => 'Gagal mendekompresi data'], 500);
        }

        // Konversi ke array JSON
        $dataall = json_decode($jsonString, true);

        return response()->json([
            'message' => 'Data berhasil didekripsi!',
            'data' => $dataall
        ]);
    }

    // Fungsi fallback internal
    private function bpjs_dekrip_internal($timestamp, $encryptedString)
    {
        $request = new Request([
            'timestamp' => $timestamp,
            'data' => $encryptedString
        ]);

        $response = $this->bpjs_dekrip($request);

        if (method_exists($response, 'getData')) {
            return $response->getData(true); // Kembalikan array
        }

        return null;
    }

    private function checkBpjsActive()
    {
        $webSetting = WebSetting::first();
        if (!$webSetting || !$webSetting->is_bpjs_active) {
            abort(response()->json([
                'status' => 'error',
                'message' => 'Fitur BPJS tidak aktif'
            ], 403));
        }
    }


}
