<?php

// Generate a new RSA key pair
$config = [
    'private_key_bits' => 2048,
    'private_key_type' => OPENSSL_KEYTYPE_RSA,
];

$keyPair = openssl_pkey_new($config);

if (!$keyPair) {
    die("Failed to generate key pair: " . openssl_error_string());
}

// Export private key
$privateKey = '';
if (!openssl_pkey_export($keyPair, $privateKey)) {
    die("Failed to export private key: " . openssl_error_string());
}

// Export public key
$publicKeyDetails = openssl_pkey_get_details($keyPair);
$publicKey = $publicKeyDetails['key'];

// Save keys
file_put_contents('storage/app/keys/paylabs_private_clean.pem', $privateKey);
file_put_contents('storage/app/keys/paylabs_public_clean.pem', $publicKey);

echo "Keys generated successfully!\n";
echo "Private key saved to: storage/app/keys/paylabs_private_clean.pem\n";
echo "Public key saved to: storage/app/keys/paylabs_public_clean.pem\n";

echo "\nPrivate key content:\n" . $privateKey . "\n";
echo "\nPublic key content:\n" . $publicKey . "\n";
