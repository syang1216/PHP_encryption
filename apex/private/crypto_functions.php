<?php

// Symmetric Encryption

// Cipher method to use for symmetric encryption
const CIPHER_METHOD = 'AES-256-CBC';

function key_encrypt($string, $key, $cipher_method=CIPHER_METHOD) {
  global $cipher_methods;
  $key_length = $cipher_methods[$cipher_method];
  $key = str_pad($key, $key_length, '*');

  $iv_length = openssl_cipher_iv_length(CIPHER_METHOD);
  $iv = openssl_random_pseudo_bytes($iv_length);
  // Encrypt
  $encrypted = openssl_encrypt($string, CIPHER_METHOD, $key, OPENSSL_RAW_DATA, $iv);
  $message = $iv . $encrypted;
  return base64_encode($message);
}

function key_decrypt($string, $key, $cipher_method=CIPHER_METHOD) {
  global $cipher_methods;
  $key_length = $cipher_methods[$cipher_method];
  $key = str_pad($key, $key_length, '*');

  $iv_with_ciphertext = base64_decode($string);
  $iv_length = openssl_cipher_iv_length(CIPHER_METHOD);
  $iv = substr($iv_with_ciphertext, 0, $iv_length);
  $ciphertext = substr($iv_with_ciphertext, $iv_length);
  // Decrypt
  $plaintext = openssl_decrypt($ciphertext, CIPHER_METHOD, $key, OPENSSL_RAW_DATA, $iv);
  return $plaintext; 
}


// Asymmetric Encryption / Public-Key Cryptography

// Cipher configuration to use for asymmetric encryption
const PUBLIC_KEY_CONFIG = array(
    "digest_alg" => "sha512",
    "private_key_bits" => 2048,
    "private_key_type" => OPENSSL_KEYTYPE_RSA,
);

function generate_keys($config=PUBLIC_KEY_CONFIG) {
   $config = array(
      "digest_alg" => "sha512",
      "private_key_bits" => 2048,
      "private_key_type" => OPENSSL_KEYTYPE_RSA,
  );
  $resource = openssl_pkey_new($config);

  // Extract private key from the pair
  openssl_pkey_export($resource, $private_key);

  // Extract public key from the pair
  $key_details = openssl_pkey_get_details($resource);
  $public_key = $key_details["key"];

  return array('private' => $private_key, 'public' => $public_key);
}

function pkey_encrypt($string, $public_key) {
  openssl_public_encrypt($string, $encrypted, $public_key);

  // Use base64_encode to make contents viewable/sharable
  return base64_encode($encrypted);
}

function pkey_decrypt($string, $private_key) {
  $ciphertext = base64_decode($string);
  openssl_private_decrypt($ciphertext, $decrypted, $private_key);

  return $decrypted;
}


// Digital signatures using public/private keys

function create_signature($data, $private_key) {
  // A-Za-z : ykMwnXKRVqheCFaxsSNDEOfzgTpYroJBmdIPitGbQUAcZuLjvlWH
  // return 'RpjJ WQL BImLcJo QLu dQv vJ oIo Iu WJu?';
  openssl_sign($data, $raw_signature, $private_key);
  return base64_encode($raw_signature);
}

function verify_signature($data, $signature, $public_key) {
  // Vigenère
  $raw_signature = base64_decode($signature);
  $result = openssl_verify($data, $raw_signature, $public_key);
  return $result;
  // returns 1 if data and signature match

  //$modified_data = $data . "extra content";
  //$result = openssl_verify($modified_data, $signature, $public_key);
  //echo $result;
}

function create_checksum($string) {
  return sha1($string);
}
// verify checksum
function verify_checksum($string, $checksum){
  return (sha1($string)===$checksum);
}
?>
