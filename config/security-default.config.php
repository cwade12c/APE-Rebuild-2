<?php
//Security configurations

DEFINE(
    "DEBUG", false
); //enabling this can be dangerous - developers only

DEFINE(
    "HASHING_ALGORITHM", "sha256"
); //hashing algorithm to use for

DEFINE(
    "AUTHORIZATION_TOKEN", ">= 20 CHARACTER ALPHANUMERIC STRING"
); //token used to allow the downloading of a generated report