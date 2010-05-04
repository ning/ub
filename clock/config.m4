dnl $Id$
dnl config.m4 for extension clock

PHP_ARG_ENABLE(clock, whether to enable clock support,
[  --enable-clock           Enable clock support])

if test "$PHP_CLOCK" != "no"; then
  PHP_NEW_EXTENSION(clock, clock.c clock-gettime.c, $ext_shared)
fi
