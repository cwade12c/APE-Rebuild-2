<?php
/**
 * test of exams
 *
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_testing
 * @subpackage     exams
 */

require_once '../config.php';

echo "testing random id generation<br><br>";

// test generating a random value
$prefix = TEMP_ID_PREFIX;
$size = TEMP_ID_BYTES_GENERATED;

$id = uniqid();
$indexStart = strlen($id) - $size;
$finalID = $prefix . substr($id, $indexStart, $size);

echo "id: {$id}</br>";
echo "id: {$finalID}</br>";

echo "</br>";
// test creating an account w/ duplicate ID

/*
echo "creating account</br>";
createAccount(
    "00333123",
    ACCOUNT_TYPE_STUDENT,
    'dummy',
    'name',
    'nospam@null'
);
echo "created</br>";

echo "creating account</br>";
createAccount(
    "00333123",
    ACCOUNT_TYPE_STUDENT,
    'dummy',
    'name',
    'nospam@null'
);
echo "created</br>";
*/

// account exists, test a retry
$fname = "not";
$lname = "real";
$email = "null@any";
$id = '00333123';

function tempPrintExceptionDetails(Exception $e, string $prefix = "|") {
    $class = get_class($e);
    $msg = $e->getMessage();
    $code = $e->getCode();
    echo "{$prefix}caught exception...</br>";
    echo "{$prefix}|     class: {$class}</br>";
    echo "{$prefix}|     code: {$code}</br>";
    echo "{$prefix}|     msg: {$msg}</br>";

    if ($previous = $e->getPrevious()) {
        $prefix .= '|';
        tempPrintExceptionDetails($previous, $prefix);
    }
}

try {
    createAccount(
        $id,
        ACCOUNT_TYPE_STUDENT,
        $fname,
        $lname,
        $email
    );
} catch (Exception $e) {
    tempPrintExceptionDetails($e);
}

// test timestamp as part of id
$t = time();
echo "time: {$t}</br>";
$hex = dechex($t);
echo "hex: {$hex}</br>";

// test new random id
echo "</br>test new random id method</br>";

$prefix = TEMP_ID_PREFIX;
$len = strlen($prefix);
echo "prefix({$len}): \"{$prefix}\"</br>";
$size = TEMP_ID_BYTES_GENERATED;
echo "generated size: {$size}</br>";

$randID = '';

$id = uniqid();
$indexStart = strlen($id) - $size;
$finalID = $prefix . substr($id, $indexStart, $size);

echo "ID: {$id}</br>";
echo "final ID: {$finalID}</br>";

function getHashes($prefix, $val) {
    echo "</br>{$prefix} hashes({$val}):</br>";
    $md5 = md5($val);
    $sha = sha1($val);
    $crc = crc32($val);
    $hcrc = dechex((int) $crc);
    echo "md5: {$md5}</br>";
    echo "sha1: {$sha}</br>";
    echo "crc32: {$crc}</br>";
    echo "hex crc32: {$hcrc}</br>";
}
getHashes("ID", $id);
getHashes("Time", time());
getHashes("Hex Time", dechex(time()));

echo "</br>test new-</br>";
$t = time();
$timeCrc = crc32($t);
$timeHex = dechex($t);
$crcHex = dechex((int) crc32($t));

echo "time: {$t}</br>crc: {$timeCrc}</br>";
echo "time hex: {$timeHex}</br>crc hex: {$crcHex}</br>";

$strT = "{$t}{$timeCrc}";
$strTHex = dechex((int) $strT);
echo "time + crc: {$strT}</br>";
echo "time + crc hex: {$strTHex}</br>";

$strTCHex = "{$timeHex}{$crcHex}";
echo "time hex + crc hex: {$strTCHex}</br>";

$id = $prefix . $strTCHex;
$len = strlen($id);
echo "id({$len}): {$id}</br>";

// will not work, timestamp alone not unique enough for short time span
echo "</br>new approach</br>";
$time = time();
$hexTime = dechex($time);
$prefix = 'T';
$maxSize = 20;
//$generate = 20 - 1 - strlen($hexTime);
$generate = 10;
$rand = random_bytes($generate);
$randHex = bin2hex($rand);

$size = strlen($rand);
echo "rand({$size}): {$rand}</br>";

$size = strlen($randHex);
echo "rand hex({$size}): {$randHex}</br>";

$encode = base64_encode($rand);
$size = strlen($encode);
echo "encode({$size}): {$encode}</br>";

$encodeAll = base64_encode($hexTime.$randHex);
$size = strlen($encodeAll);
echo "encode time and rand({$size}): {$encodeAll}</br>";

$encodeAgain = base64_encode(((string) $time).$rand);
$size = strlen($encodeAgain);
echo "encode #2({$size}): {$encodeAgain}</br>";

echo "random ID: {$prefix}{$hexTime}{$randHex}</br>";

/*
 * 3 key parts to id
 *  prefix
 *  timestamp
 *  random bytes
 * max size is key
 *
 * need to generate random bytes to correct size
 * 64 bit encoding increases size (30-50%)
 * could just chop it in half, don't really matter
 *
 */

echo "</br>final approach?</br>";

function testGen($prefix, $size) {
    $generate = $size - strlen($prefix);

    $time = time();
    $rand = random_bytes($generate);
    $pack = pack("iA*", $time, $rand);
    $encode = base64_encode($pack);
    $id = $prefix . substr($encode, 0, $generate);

    $len = strlen($id);
    echo "id ({$len}): {$id}</br>";
}

testGen('T', 20);
testGen('TEMP', 20);

echo "<br><br>EOLFL";
