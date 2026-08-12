<?php

function request($method, $url, $postData = [], $cookieFile = 'cookie.txt') {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false); // We want to see redirects explicitly
    curl_setopt($ch, CURLOPT_HEADER, true);
    
    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    }
    
    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    // Extract headers and body
    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $header = substr($response, 0, $header_size);
    $body = substr($response, $header_size);
    
    curl_close($ch);
    return ['code' => $httpcode, 'header' => $header, 'body' => $body];
}

function getCsrfToken($body) {
    preg_match('/<input type="hidden" name="_token" value="(.*?)">/', $body, $matches);
    return $matches[1] ?? null;
}

$baseUrl = 'http://103.89.4.245';
$cookieFile = __DIR__ . '/cookie.txt';

$accounts = [
    'Super Admin' => ['email' => 'superadmin@mandalajati.com', 'password' => 'password', 'urls' => [
        '/superadmin/dashboard',
        '/superadmin/users',
        '/superadmin/laporan',
        '/superadmin/master/kategori-umkm',
    ]],
    'Admin' => ['email' => 'admin@mandalajati.com', 'password' => 'password', 'urls' => [
        '/admin/dashboard',
        '/admin/laporan',
        '/admin/umkm',
        '/admin/verifikasi',
    ]],
    'Operator' => ['email' => 'operator1@mandalajat.com', 'password' => 'password', 'urls' => [
        '/operator/dashboard',
        '/operator/umkm',
        '/operator/verifikasi',
    ]],
    'Pelaku' => ['email' => 'ikhsanocta12@gmail.com', 'password' => 'password', 'urls' => [
        '/dashboard',
        '/pelaku/pengajuan',
    ]]
];

echo "Starting Tests on {$baseUrl}\n";
echo "========================================\n";

foreach ($accounts as $role => $data) {
    echo "\nTesting Role: {$role} ({$data['email']})\n";
    if (file_exists($cookieFile)) unlink($cookieFile);
    
    // 1. Load login page to get CSRF token
    $res = request('GET', $baseUrl . '/login', [], $cookieFile);
    $token = getCsrfToken($res['body']);
    
    if (!$token) {
        echo "  [FAIL] Could not extract CSRF token from login page.\n";
        continue;
    }
    
    // 2. Perform Login
    $loginData = [
        '_token' => $token,
        'email' => $data['email'],
        'password' => $data['password']
    ];
    $loginRes = request('POST', $baseUrl . '/login', $loginData, $cookieFile);
    
    if ($loginRes['code'] == 302) {
        echo "  [SUCCESS] Login successful (HTTP 302)\n";
    } else {
        echo "  [FAIL] Login failed! HTTP Code: {$loginRes['code']}\n";
        continue;
    }
    
    // Follow redirect to dashboard to complete session init
    preg_match('/Location: (.*?)\n/i', $loginRes['header'], $matches);
    $redirectUrl = trim($matches[1] ?? '');
    if ($redirectUrl) request('GET', $redirectUrl, [], $cookieFile);
    
    // 3. Test Endpoints
    foreach ($data['urls'] as $path) {
        $testRes = request('GET', $baseUrl . $path, [], $cookieFile);
        $code = $testRes['code'];
        
        if ($code == 200) {
            echo "  [OK] GET {$path} -> HTTP 200\n";
        } elseif ($code == 302) {
             echo "  [WARN] GET {$path} -> HTTP 302 (Redirect)\n";
        } elseif ($code >= 500) {
            echo "  [ERROR] GET {$path} -> HTTP {$code} (SERVER ERROR)\n";
        } else {
            echo "  [WARN] GET {$path} -> HTTP {$code}\n";
        }
    }
}
echo "\nTesting Complete.\n";
