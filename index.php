<?php
/**
 * =========================================================================
 * TrafficGuard Webmaster Suite - Flow Connector Engine
 * Flow ID: fl-5593
 * Flow Name: Tiktok ads first set up
 * Group: TikTok Ads
 * Generated: 2026-11-24
 * Mode: reverse_proxy
 * =========================================================================
 * INSTRUCTIONS:
 * 1. Upload this file as 'index.php' into your website's public_html folder
 *    on Namecheap (or your campaign landing page hosting).
 * 2. Ensure PHP 7.4+ or 8.x is active on your server with cURL enabled.
 * =========================================================================
 */

error_reporting(0);

if (function_exists('mb_internal_encoding')) {
    mb_internal_encoding('UTF-8');
}

if (version_compare(PHP_VERSION, '7.4', '<')) {
    exit('PHP 7.4 or higher is required to run TrafficGuard.');
}

// -------------------------------------------------------------------------
// Configuration Settings
// -------------------------------------------------------------------------
$flow_config = [
    'flow_id'       => 'fl-5593',
    'flow_name'     => 'Tiktok ads first set up',
    'safe_page'     => 'https://dailytrends1.github.io/wizzy/',
    'target_page'   => 'https://dailytrends1.github.io/wizzymain/',
    'mode'          => 'reverse_proxy', // 'reverse_proxy', 'redirect', 'iframe'
    'block_bots'    => true,
    'block_dc_asns' => true,
];

// -------------------------------------------------------------------------
// Client Telemetry Extraction
// -------------------------------------------------------------------------
function tg_get_client_ip() {
    $headers = [
        'HTTP_CF_CONNECTING_IP',
        'HTTP_TRUE_CLIENT_IP',
        'HTTP_X_REAL_IP',
        'HTTP_X_FORWARDED_FOR',
        'REMOTE_ADDR'
    ];

    foreach ($headers as $header) {
        if (!empty($_SERVER[$header])) {
            $ips = explode(',', $_SERVER[$header]);
            foreach ($ips as $ip) {
                $ip = trim($ip);
                $ip = preg_replace('/:\d+$/', '', $ip);
                $ip = trim($ip, '[]');
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }
    }
    return !empty($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
}

function tg_get_user_agent() {
    return !empty($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
}

function tg_get_referer() {
    return !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
}

function tg_create_stream_context() {
    return stream_context_create([
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false
        ],
        'http' => [
            'header' => 'User-Agent: ' . tg_get_user_agent()
        ]
    ]);
}

// -------------------------------------------------------------------------
// Defensive Bot & Spider Heuristic Detection
// -------------------------------------------------------------------------
function tg_is_automated_traffic($ua) {
    if (empty($ua)) return true;

    // Known ad review crawlers, search spiders, and automated test frameworks
    $bot_patterns = [
        'googlebot', 'adsbot-google', 'google-adwords-instant', 'mediapartners-google',
        'facebookexternalhit', 'facebot', 'facebookplatform',
        'tiktokspider', 'bytedancespider',
        'twitterbot', 'linkedinbot', 'pinterestbot', 'slackbot',
        'ahrefsbot', 'semrushbot', 'mj12bot', 'dotbot', 'petalbot',
        'headlesschrome', 'phantomjs', 'selenium', 'puppeteer', 'playwright',
        'python-requests', 'curl/', 'wget/', 'go-http-client', 'postmanruntime'
    ];

    $ua_lower = strtolower($ua);
    foreach ($bot_patterns as $pattern) {
        if (strpos($ua_lower, $pattern) !== false) {
            return true;
        }
    }
    return false;
}

// -------------------------------------------------------------------------
// Decision Evaluation
// -------------------------------------------------------------------------
$client_ip = tg_get_client_ip();
$client_ua = tg_get_user_agent();
$is_bot = tg_is_automated_traffic($client_ua);

// If bot detected, route to Safe Page; legitimate visitor routes to Target Page
$destination = $is_bot ? 'safe' : 'target';

// -------------------------------------------------------------------------
// Delivery Execution
// -------------------------------------------------------------------------
if ($destination === 'target') {
    $url = $flow_config['target_page'];
    $mode = $flow_config['mode'];

    if ($mode === 'redirect') {
        header('Location: ' . $url, true, 302);
        exit(0);
    }

    if ($mode === 'iframe') {
        echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Loading...</title><style>body,html{margin:0;padding:0;height:100%;overflow:hidden;}iframe{border:0;width:100%;height:100%;}</style></head><body><iframe src="' . htmlspecialchars($url) . '"></iframe></body></html>';
        exit(0);
    }

    // Default: reverse_proxy / loading
    if (filter_var($url, FILTER_VALIDATE_URL)) {
        $content = file_get_contents($url, false, tg_create_stream_context());
        if ($content !== false) {
            echo str_replace('<head>', '<head><base href="' . htmlspecialchars($url) . '" />', $content);
            exit(0);
        }
    }
    header('Location: ' . $url, true, 302);
    exit(0);
} else {
    // Deliver Safe Page (White Page)
    $url = $flow_config['safe_page'];

    if ($flow_config['mode'] === 'redirect') {
        header('Location: ' . $url, true, 302);
        exit(0);
    }

    if (filter_var($url, FILTER_VALIDATE_URL)) {
        $content = file_get_contents($url, false, tg_create_stream_context());
        if ($content !== false) {
            echo str_replace('<head>', '<head><base href="' . htmlspecialchars($url) . '" />', $content);
            exit(0);
        }
    }
    header('Location: ' . $url, true, 302);
    exit(0);
}
?>
