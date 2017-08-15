<?php
/**
 * Test functions, helpers
 *
 * @author          Mathew McCain
 * @category        APE
 * @package         APE_includes
 * @subpackage      Testing
 */

/**
 * Check if request is a post
 * https://stackoverflow.com/questions/4242035/checking-if-request-is-post-back-in-php
 */
function isPost()
{
    return (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST');
}