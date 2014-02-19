<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | PHP Version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2004 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 3.0 of the PHP license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available at through the world-wide-web at                           |
// | http://www.php.net/license/3_0.txt.                                  |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: Aidan Lister <aidan@php.net>                                |
// +----------------------------------------------------------------------+
//
// $Id: vsprintf.php,v 1.3 2009/05/04 10:08:40 cvstest Exp $
//


/**
 * Replace vsprintf()
 *
 * @category    PHP
 * @package     PHP_Compat
 * @link        http://php.net/function.vsprintf
 * @author      Aidan Lister <aidan@php.net>
 * @version     $Revision: 1.3 $
 * @since       PHP 4.1.0
 */
if (!function_exists('vsprintf'))
{
    function vsprintf ($format, $args)
    {
        if (count($args) < 2)
        {
            trigger_error('vsprintf() Too few arguments', E_USER_WARNING);
            return null;
        }

        array_unshift($args, $format);
        return call_user_func_array('sprintf', $args);
    }
}
?>