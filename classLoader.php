<?php

$classes  = array_slice(scandir('App'), 2);

$filteredClasses = [];
foreach ($classes as $class) {
    $interfaceSubStr = stripos($class, 'Interface');
    $abstractSubStr  = stripos($class, 'Abstract');
    $baseSubStr      = stripos($class, 'FileHandler');
    $adapterSubStr   = stripos($class, 'Adapter');

    if ($baseSubStr === 0) {
        array_unshift($filteredClasses, $class);
    } elseif ($abstractSubStr === 0) {
        array_unshift($filteredClasses, $class);
    } elseif ($interfaceSubStr) {
        array_unshift($filteredClasses, $class);
    } elseif (!$adapterSubStr) {
        $filteredClasses[] = $class;
    } elseif ($adapterSubStr) {
        $filteredClasses[] = $class;
    }
}

foreach ($filteredClasses as $class) {
    require_once 'App/' . $class;
}
