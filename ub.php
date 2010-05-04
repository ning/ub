<?php

/** Simple getopt()-like option parser */
function ub_parse_opts($expected,$args) {
    /* _ is the key for unexpected opts */
    $parsed = array('_' => array());
    preg_match_all('/([a-z]:?)/', $expected, $matches);
    foreach ($matches[1] as $match) {
        /* Default value for opts not expecting a value is "false",
         * For opts expecting a value, it's "null" */
        $parsed[$match[0]] = (strlen($match) == 1) ? false : null;
    }
    $current_opt = '_';
    foreach ($args as $arg) {
        /* If current arg begins with "-", it's probably an option */
        if ($arg[0] == '-') {
            $current_opt = substr($arg, 1);
            /* Is it expected ?*/
            if (array_key_exists($current_opt, $parsed)) {
                /* Set no-value (boolean) opt to true */
                if ($parsed[$current_opt] === false) {
                    $parsed[$current_opt] = true;
                    $current_opt = '_';
                }
            }
            /* Unexpected, toss it onto the list */
            else {
                $parsed['_'][] = $arg;
            }
        }
        /* Current arg doesn't begin with "-", it's probably a value */
        else {
            /* If we're not expecting a value (or at the start), toss it onto the unexpected list */
            if ($current_opt == '_') {
                $parsed['_'][] = $arg;
            }
            /* If we are expecting ... */
            else if (array_key_exists($current_opt, $parsed)) {
                /* Regular ol' expected value */
                if (is_null($parsed[$current_opt])) {
                    $parsed[$current_opt] = $arg;
                }
                /* Value after a boolean option, put the value on the unexpected list */
                else if (is_bool($parsed[$current_opt])) {
                    $parsed['_'] = $arg;
                }
                /* We've already seen multiple values for this opt, add the new value */
                else if (is_array($parsed[$current_opt])) {
                    $parsed[$current_opt][] = $arg;
                } 
                /* The second value for this opt, convert to an array */
                else {
                    $parsed[$current_opt] = array($parsed[$current_opt], $arg);
                }          
                /* Reset current_opt flag so we can look for a new option next */
                $current_opt = '_';
            }
            /* Unexpected, toss it onto the list */
            else {
                $parsed['_'][] = $arg;
            }
        }
    }
    return $parsed;
}

/** Chop up the provided PHP code into separate blocks based on the
 * state transitions denoted by comments in the code */
function ub_parse_code($code) {

    /* What state we start in */
    $state = 'start';
    /* Allowable transitions: key is the "from" state and value is
     * an array of acceptable "to" states. A state transition is denoted
     * in the code by a single line comment like "// new-state-name"
     */
    $transitions = array('start' => array('once', 'init', 'time','ignore'),
                         'once' => array('init', 'time','ignore'),
                         'init' => array('time', 'done','ignore'),
                         'time' => array('done', 'ignore'),
                         'done' => array('ignore')
                         );

    /* Where the chopped up code will be stored */
    $blocks = array();
    
    /* This is a little inefficient, but prevents having to update some
     * hardcoded $blocks initialization code when $transitions changes */
    foreach ($transitions as $from => $to) {
        if (! isset($blocks[$from])) {
            $blocks[$from] = '';
        }
        foreach ($to as $to_state) {
            if (! isset($blocks[$to_state])) {
                $blocks[$to_state] = '';
            }
        }
    }
    $states_rx = join("|", array_keys($blocks));

    $tokens = token_get_all($code);
    
    /* Trim off whitespace-after-closing-tag if that's at the end of the token list */
    $last_token = end($tokens);
    if (($last_token[0] == T_INLINE_HTML) && preg_match('/^\s+$/u', $last_token[1])) {
        array_pop($tokens);
    }
    /* Trim off a closing tag if that's at the end of the token list */
    $last_token = end($tokens);
    if ($last_token[0] == T_CLOSE_TAG) {
        array_pop($tokens);
    }

    foreach ($tokens as $tok) {
        /* Standardize token representation as an array */
        if (! is_array($tok)) { $tok = array(T_STRING, $tok); }

        if (($tok[0] == T_COMMENT) && /* regular comment */
            /* Referencing a known state */
            preg_match('@^//\s*('. $states_rx . ')$@s', trim($tok[1]), $matches) &&
            /* That the current state can transition to */
            in_array($matches[1], $transitions[$state])) {
            /* Then transition to that state */
            $state = $matches[1];
        }
        /* Otherwise, just concatenate the current token onto this state's code block */
        else {
            $blocks[$state] .= $tok[1];
        }
    }

    if (strlen($blocks['start']) == array_sum(array_map('strlen', $blocks))) {
        $blocks['time'] = '?>' . $blocks['start'];
        $blocks['start'] = '';
    }
    
    return $blocks;
}

/** Generate a function that will invoke the microbenchmark the appropriate number of
 * times. The microbenchmark is run inside a function to protect its variable scope */
function ub_generate_function($blocks, $iter, $verbose = false, $use_clock = false, $___opts = array()) {
    if ($use_clock) {
        $stamp = 'clock_gettime(0)';
        $diff = '
$___diff_sec = $___end[0] - $___start[0];
if ($___diff_sec > 0) {
   $___end[1] += ($___diff_sec * 1000000000);
}
$___diff_nsec = $___end[1] - $___start[1];
$___times[] = ($___diff_nsec / 1000000);
';
    } else {
        $stamp = 'gettimeofday()';
        $diff = '
$___diff_sec = $___end["sec"] - $___start["sec"];
if ($___diff_sec > 0) {
   $___end["usec"] += ($___diff_sec * 1000000); 
}
$___diff_usec = $___end["usec"] - $___start["usec"];
$___times[] = ($___diff_usec / 1000);
';
    }
    $s = '
$___times = array();
$___opts = '.var_export($___opts, true) .';
'.$blocks['once'].'
for ($___i = 0; $___i < '.$iter.'; $___i++) {
    '.$blocks['init'].'
    $___start = '.$stamp.';
    '.$blocks['time'].'
    $___end = '.$stamp.';
    '. $diff .'
}
'.$blocks['done'].'
return $___times;
';
    if ($verbose) {
        print "Running function() { $s}\n";
    }
    return create_function('', $s);
}
  
/** Given a label and an array of execution times, print out some useful statistics
 * about the times */
function ub_print_results($label, $times) {
    sort($times);
    $min = $times[0];
    $max = $times[count($times) - 1];
    if ((count($times) % 2) == 1) {
        $median = $times[ floor(count($times) / 2) ];
    } else {
        $median = ($times[ count($times) / 2 ] + $times[ (count($times) / 2) -1 ]) / 2;
    }
    $mean = array_sum($times) / count($times);
    
    $variance = 0.0;
    foreach ($times as $time) {
        $variance += pow(($time - $mean), 2);
    }
    $variance = $variance / (count($times) - 1);
    $stdev = sqrt($variance);

    printf("%32s: mean=%.06f median=%.06f min=%.06f max=%.06f stdev=%.06f\n",
           $label, $mean, $median, $min, $max, $stdev);
}
