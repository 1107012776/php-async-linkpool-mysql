<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2019/5/29
 * Time: 6:58 AM
 */
function getCurrentTime ()
{
    list ($msec, $sec) = explode(" ", microtime());
    return (float)$msec + (float)$sec;
}