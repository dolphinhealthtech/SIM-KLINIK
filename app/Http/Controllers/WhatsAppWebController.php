<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WhatsAppWebController extends Controller
{
     /**
     * Display WhatsApp Gateway Dashboard
     */
    public function dashboard()
    {
        return view('dashboard', [
            'title' => 'WhatsApp Gateway Dashboard',
            'page' => 'whatsapp-dashboard'
        ]);
    }

    /**
     * Display specific session management page
     */
    public function sessionDetail($sessionId)
    {
        return view('whatsapp.session-detail', [
            'title' => "Session: {$sessionId}",
            'sessionId' => $sessionId,
            'page' => 'whatsapp-session'
        ]);
    }

    /**
     * API Documentation page
     */
    public function apiDocs()
    {
        $endpoints = [
            'Session Management' => [
                [
                    'method' => 'GET',
                    'endpoint' => '/api/whatsapp/sessions',
                    'description' => 'Get all WhatsApp sessions',
                    'parameters' => 'None',
                    'response' => 'List of sessions with status'
                ],
                [
                    'method' => 'GET',
                    'endpoint' => '/api/whatsapp/session/{sessionId}/status',
                    'description' => 'Get specific session status',
                    'parameters' => 'sessionId (string)',
                    'response' => 'Session status and readiness'
                ],
                [
                    'method' => 'GET',
                    'endpoint' => '/api/whatsapp/session/{sessionId}/qr',
                    'description' => 'Get QR code for session login',
                    'parameters' => 'sessionId (string)',
                    'response' => 'QR code data and image'
                ],
                [
                    'method' => 'POST',
                    'endpoint' => '/api/whatsapp/session/{sessionId}/logout',
                    'description' => 'Logout session',
                    'parameters' => 'sessionId (string)',
                    'response' => 'Logout confirmation'
                ],
                [
                    'method' => 'DELETE',
                    'endpoint' => '/api/whatsapp/session/{sessionId}/delete',
                    'description' => 'Delete session permanently',
                    'parameters' => 'sessionId (string)',
                    'response' => 'Delete confirmation'
                ],
                [
                    'method' => 'POST',
                    'endpoint' => '/api/whatsapp/session/{sessionId}/restart',
                    'description' => 'Restart session',
                    'parameters' => 'sessionId (string)',
                    'response' => 'Restart confirmation'
                ]
            ],
            'Token Management' => [
                [
                    'method' => 'GET',
                    'endpoint' => '/api/whatsapp/session/{sessionId}/token',
                    'description' => 'Get token information',
                    'parameters' => 'sessionId (string)',
                    'response' => 'Token details and usage'
                ],
                [
                    'method' => 'POST',
                    'endpoint' => '/api/whatsapp/session/{sessionId}/token',
                    'description' => 'Set premium token',
                    'parameters' => 'sessionId, token, limit, expiryHours, active',
                    'response' => 'Token creation confirmation'
                ],
                [
                    'method' => 'POST',
                    'endpoint' => '/api/whatsapp/session/{sessionId}/token/status',
                    'description' => 'Update token status',
                    'parameters' => 'sessionId, active (boolean)',
                    'response' => 'Token status update confirmation'
                ],
                [
                    'method' => 'DELETE',
                    'endpoint' => '/api/whatsapp/session/{sessionId}/token',
                    'description' => 'Delete premium token',
                    'parameters' => 'sessionId (string)',
                    'response' => 'Token deletion confirmation'
                ]
            ],
            'Message Sending' => [
                [
                    'method' => 'POST',
                    'endpoint' => '/api/whatsapp/session/{sessionId}/send',
                    'description' => 'Send text message',
                    'parameters' => 'sessionId, number, message, token (optional)',
                    'response' => 'Send confirmation and token usage'
                ],
                [
                    'method' => 'POST',
                    'endpoint' => '/api/whatsapp/session/{sessionId}/send-template',
                    'description' => 'Send template message',
                    'parameters' => 'sessionId, number, template, templateData, token',
                    'response' => 'Template send confirmation'
                ]
            ],
            'Debug & Testing' => [
                [
                    'method' => 'GET',
                    'endpoint' => '/api/whatsapp/debug/folders',
                    'description' => 'Debug session folders',
                    'parameters' => 'None',
                    'response' => 'Folder structure information'
                ],
                [
                    'method' => 'POST',
                    'endpoint' => '/api/whatsapp/refresh-sessions',
                    'description' => 'Refresh all sessions',
                    'parameters' => 'None',
                    'response' => 'Refresh confirmation'
                ],
                [
                    'method' => 'POST',
                    'endpoint' => '/api/whatsapp/test',
                    'description' => 'Test endpoint connectivity',
                    'parameters' => 'Any data',
                    'response' => 'Test response'
                ]
            ]
        ];

        return view('whatsapp.api-docs', [
            'title' => 'WhatsApp API Documentation',
            'endpoints' => $endpoints,
            'page' => 'whatsapp-api-docs'
        ]);
    }
}
