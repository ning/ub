/*
  +----------------------------------------------------------------------+
  | PHP Version 5                                                        |
  +----------------------------------------------------------------------+
  | Copyright (c) 1997-2008 The PHP Group                                |
  +----------------------------------------------------------------------+
  | This source file is subject to version 3.01 of the PHP license,      |
  | that is bundled with this package in the file LICENSE, and is        |
  | available through the world-wide-web at the following url:           |
  | http://www.php.net/license/3_01.txt                                  |
  | If you did not receive a copy of the PHP license and are unable to   |
  | obtain it through the world-wide-web, please send a note to          |
  | license@php.net so we can mail you a copy immediately.               |
  +----------------------------------------------------------------------+
  | Author:                                                              |
  +----------------------------------------------------------------------+
*/

/* $Id: header 252479 2008-02-07 19:39:50Z iliaa $ */

#ifndef PHP_CLOCK_H
#define PHP_CLOCK_H

extern zend_module_entry clock_module_entry;
#define phpext_clock_ptr &clock_module_entry

#ifdef PHP_WIN32
#	define PHP_CLOCK_API __declspec(dllexport)
#elif defined(__GNUC__) && __GNUC__ >= 4
#	define PHP_CLOCK_API __attribute__ ((visibility("default")))
#else
#	define PHP_CLOCK_API
#endif

#ifdef ZTS
#include "TSRM.h"
#endif

PHP_MINIT_FUNCTION(clock);
PHP_MSHUTDOWN_FUNCTION(clock);
PHP_MINFO_FUNCTION(clock);

PHP_FUNCTION(clock_gettime);

#ifdef ZTS
#define CLOCK_G(v) TSRMG(clock_globals_id, zend_clock_globals *, v)
#else
#define CLOCK_G(v) (clock_globals.v)
#endif

#endif	/* PHP_CLOCK_H */


/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * End:
 * vim600: noet sw=4 ts=4 fdm=marker
 * vim<600: noet sw=4 ts=4
 */
