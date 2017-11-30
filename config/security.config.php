<?php
// Security configurations

// hashing algorithm to use
DEFINE('HASHING_ALGORITHM', 'sha256');

/*
 * Used for hashing account IDs, to mask from graders
 * Due to format of account IDs, can easily check for collisions
 * Use scripts/collisions.py to check
 */
DEFINE('ACCOUNT_ID_HASH_ALGORITHM', 'md5');
DEFINE('ACCOUNT_ID_HASH_LENGTH', 12);

// token used to allow the downloading of a generated report
DEFINE('AUTHORIZATION_TOKEN', '>= 20 CHARACTER ALPHANUMERIC STRING');