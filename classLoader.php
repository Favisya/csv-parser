<?php

$classes  = array_slice(scandir('App'), 2);

$filteredClasses = [];
foreach ($classes as $class) {
    $interfaceSubStr = stripos($class, 'Interface');
    $abstractSubStr  = stripos($class, 'Abstract');
    $baseSubStr      = stripos($class, 'FileHandler');

    if ($baseSubStr === 0 || $abstractSubStr === 0 || $interfaceSubStr) {
        array_unshift($filteredClasses, $class);
    } else {
        $filteredClasses[] = $class;
    }
}

foreach ($filteredClasses as $class) {
    require_once 'App/' . $class;
}
