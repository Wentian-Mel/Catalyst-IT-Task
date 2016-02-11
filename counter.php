<?php

for ($index = 1; $index <= 100; $index++) {
    if (!empty(triplefiver($index))) {
        echo triplefiver($index) . PHP_EOL;
        continue;
    } elseif (!empty(triple($index))) {
        echo triple($index) . PHP_EOL;
        continue;
    } elseif (!empty(fiver($index))) {
        echo fiver($index) . PHP_EOL;
        continue;
    } else {
        echo $index . PHP_EOL;
    }
}

function triple($index) {
    if ($index % 3 == 0) {
        return "triple";
    }
}

function fiver($index) {
    if ($index % 5 == 0) {
        return "fiver";
    }
}

function triplefiver($index) {
    if ($index % 3 == 0 && $index % 5 == 0) {
        return "triplefiver";
    }
}

?>