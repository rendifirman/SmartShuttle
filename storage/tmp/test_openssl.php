<?php
$k = file_get_contents(__DIR__ . '/../keys/paylabs_private.pem');
$r = openssl_pkey_get_private($k);
if ($r) {
    echo "OK\n";
} else {
    echo "ERROR: " . openssl_error_string() . "\n";
}
?>
