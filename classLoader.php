<?php

$classes  = array_slice(scandir('App'), 2);

$filteredClasses = [];
foreach ($classes as $class) {
    $isInterface = stripos($class, 'Interface') !== false;
    $isAbstract  = stripos($class, 'Abstract') !== false;
    $isMainApp   = stripos($class, 'FileHandler') !== false;

    if ($isMainApp || $isInterface || $isAbstract) {
        array_unshift($filteredClasses, $class);        
    } else {
        $filteredClasses[] = $class;
    }
}

foreach ($filteredClasses as $class) {
    require_once 'App/' . $class;
}
