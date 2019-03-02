<?php

declare(strict_types=1);

if (function_exists('dd') === false) {
    function dd(...$vars)
    {
        foreach ($vars as $var) {
            var_dump($var);
        }

        die(1);
    }
}

if (function_exists('dump') === false) {
    function dump(...$vars)
    {
        foreach ($vars as $var) {
            var_dump($var);
        }
    }
}
