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
// $Id: file_get_contents.php,v 1.3 2009/05/04 10:08:40 cvstest Exp $
//


/**
 * Replace file_get_contents()
 *
 * @category    PHP
 * @package     PHP_Compat
 * @link        http://php.net/function.file_get_contents
 * @author      Aidan Lister <aidan@php.net>
 * @version     $Revision: 1.3 $
 * @internal    $resource_context is not supported
 * @since       PHP 5
 */
if (!function_exists('file_get_contents'))
{
    function file_get_contents ($filename, $incpath = false, $resource_context = null)
    {
        if ($fh = @fopen($filename, 'rb', $incpath) === false) {
            trigger_error('file_get_contents() failed to open stream: No such file or directory', E_USER_WARNING);
            return false;
        }

        clearstatcache();
        if ($fsize = filesize($filename)) {
            $data = fread($fh, $fsize);
        }
        
        else {
            while (!feof($fh)) {
                $data .= fread($fh, 8192);
            }
        }

        fclose($fh);

        return $data;    
    }
}

?>