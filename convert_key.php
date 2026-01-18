<?php

// Read the XML key
$xmlContent = file_get_contents('storage/app/keys/paylabs_private.xml');

// Parse XML
$xml = simplexml_load_string($xmlContent);

// Extract components
$modulus = (string)$xml->Modulus;
$exponent = (string)$xml->Exponent;
$p = (string)$xml->P;
$q = (string)$xml->Q;
$dp = (string)$xml->DP;
$dq = (string)$xml->DQ;
$inverseQ = (string)$xml->InverseQ;
$d = (string)$xml->D;

// Decode base64
$keyDetails = [
    'n' => base64_decode($modulus),
    'e' => base64_decode($exponent),
    'd' => base64_decode($d),
    'p' => base64_decode($p),
    'q' => base64_decode($q),
    'dp' => base64_decode($dp),
    'dq' => base64_decode($dq),
    'qi' => base64_decode($inverseQ),
];

// Create RSA key
$rsaKey = openssl_pkey_new(['rsa' => $keyDetails]);

if (!$rsaKey) {
    die("Failed to create RSA key: " . openssl_error_string());
}

// Export to PEM
$pem = '';
if (!openssl_pkey_export($rsaKey, $pem)) {
    die("Failed to export key: " . openssl_error_string());
}

// Save to file
file_put_contents('storage/app/keys/paylabs_private_clean.pem', $pem);

echo "PEM key saved to storage/app/keys/paylabs_private_clean.pem\n";
echo "PEM content:\n" . $pem . "\n";
