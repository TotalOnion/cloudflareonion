<?php

function cfoGetKey() {
    $key = get_option(GLOBAL_CFO_NAME.'_encrypt_key');
    if(!$key) {
        $key = openssl_random_pseudo_bytes(10);
        add_option(GLOBAL_CFO_NAME.'_encrypt_key', $key);
    }
    return $key;
}

function cfoGetIV() {
    $iv = get_option(GLOBAL_CFO_NAME.'_encrypt_iv');
    if(!$iv) {
        $ivlen = openssl_cipher_iv_length('aes-256-cbc');
        $iv = base64_encode(openssl_random_pseudo_bytes($ivlen));
        add_option(GLOBAL_CFO_NAME.'_encrypt_iv', $iv);
    }
    return base64_decode($iv);
}

function cfoEncryptInput($input) {
    $sanitizedInput = sanitize_text_field($input);
    $key = cfoGetKey();
    $iv = cfoGetIV();

    $encryptedInput = openssl_encrypt($sanitizedInput, 'aes-256-cbc', $key, 0, $iv);
    return $encryptedInput;
}

function cfoDecryptInput($encryptedValue) {
    $key = cfoGetKey();
    $iv = cfoGetIV();
    $decryptedValue = openssl_decrypt($encryptedValue, 'aes-256-cbc', $key, 0, $iv);
    return $decryptedValue;
}