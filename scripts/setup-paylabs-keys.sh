#!/bin/bash

# Script setup untuk generate RSA keys untuk Paylabs
# Usage: bash scripts/setup-paylabs-keys.sh

echo "Setting up Paylabs RSA Keys..."

# Buat direktori jika belum ada
mkdir -p storage/app/keys

# Generate private key (2048 bit)
echo "Generating private key..."
openssl genrsa -out storage/app/keys/paylabs_private.pem 2048

# Generate public key dari private key
echo "Generating public key..."
openssl rsa -in storage/app/keys/paylabs_private.pem -pubout -out storage/app/keys/paylabs_public.pem

# Set permissions
chmod 600 storage/app/keys/paylabs_private.pem
chmod 644 storage/app/keys/paylabs_public.pem

echo "Keys generated successfully!"
echo "Private Key: storage/app/keys/paylabs_private.pem"
echo "Public Key: storage/app/keys/paylabs_public.pem"

# Tampilkan sample untuk .env
echo ""
echo "=== SAMPLE .env CONFIGURATION ==="
echo "PAYLABS_PRIVATE_KEY_FILE=storage/app/keys/paylabs_private.pem"
echo "PAYLABS_PUBLIC_KEY_FILE=storage/app/keys/paylabs_public.pem"
