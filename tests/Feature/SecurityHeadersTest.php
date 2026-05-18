<?php

test('web responses contain essential security headers', function () {
    $response = $this->get('/login');

    $response->assertHeader('X-Frame-Options', 'DENY');
    $response->assertHeader('X-Content-Type-Options', 'nosniff');
    $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    $response->assertHeader('Content-Security-Policy');
    $response->assertHeader('Permissions-Policy');
});

test('hsts header is present in production', function () {
    config(['app.env' => 'production']);
    
    $response = $this->get('/login');

    $response->assertHeader('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
});

test('hsts header is absent in local environment', function () {
    config(['app.env' => 'local']);
    
    $response = $this->get('/login');

    $this->assertFalse($response->headers->has('Strict-Transport-Security'));
});
