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

#ifdef HAVE_CONFIG_H
#include "config.h"
#endif

#include "php.h"
#include "php_ini.h"
#include "ext/standard/info.h"
#include "php_clock.h"
#include "clock-gettime.h"

/* If you declare any globals in php_clock.h uncomment this:
ZEND_DECLARE_MODULE_GLOBALS(clock)
*/

/* True global resources - no need for thread safety here */
// static int le_clock;

/* {{{ clock_functions[]
 *
 * Every user visible function must have an entry in clock_functions[].
 */
const zend_function_entry clock_functions[] = {
    PHP_FE(clock_gettime, NULL)
	{NULL, NULL, NULL}	/* Must be the last line in clock_functions[] */
};
/* }}} */

/* {{{ clock_module_entry
 */
zend_module_entry clock_module_entry = {
#if ZEND_MODULE_API_NO >= 20010901
	STANDARD_MODULE_HEADER,
#endif
	"clock",
	clock_functions,
	PHP_MINIT(clock),
	PHP_MSHUTDOWN(clock),
    NULL, /* PHP_RINIT(clock), */
	NULL, /* PHP_RSHUTDOWN(clock), */
	PHP_MINFO(clock),
#if ZEND_MODULE_API_NO >= 20010901
	"0.1", 
#endif
	STANDARD_MODULE_PROPERTIES
};
/* }}} */

#ifdef COMPILE_DL_CLOCK
ZEND_GET_MODULE(clock)
#endif

/* {{{ PHP_INI
 */
/* PHP_INI_BEGIN() */
/* PHP_INI_END() */
/* }}} */

/* {{{ PHP_MINIT_FUNCTION
 */
PHP_MINIT_FUNCTION(clock)
{
	return SUCCESS;
}
/* }}} */

/* {{{ PHP_MSHUTDOWN_FUNCTION
 */
PHP_MSHUTDOWN_FUNCTION(clock)
{
	return SUCCESS;
}
/* }}} */

/* {{{ PHP_MINFO_FUNCTION
 */
PHP_MINFO_FUNCTION(clock)
{
	php_info_print_table_start();
	php_info_print_table_header(2, "clock support", "enabled");
	php_info_print_table_end();

}
/* }}} */

/* {{{ proto array clock_gettime(int clock_type)
   Return the time from the given clock */
PHP_FUNCTION(clock_gettime)
{
    long clock_type;
    struct timespec tp;
    int c;
    if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "l", &clock_type) == FAILURE) {
        return;
    }

    /* todo actually match clock_type to a defined constant */
    c = clock_gettime(CLOCK_MONOTONIC, &tp);

    array_init(return_value);
    add_next_index_long(return_value, tp.tv_sec);
    add_next_index_long(return_value, tp.tv_nsec);

    return;
}
/* }}} */

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * End:
 * vim600: noet sw=4 ts=4 fdm=marker
 * vim<600: noet sw=4 ts=4
 */
