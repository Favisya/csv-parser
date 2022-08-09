<?php

$classes  = array_slice(scandir('App'), 2);

$filteredClasses = [];
foreach ($classes as $class) {
    (bool) $isInterfaceSubStr = stripos($class, 'Interface');
    (bool) $isAbstractSubStr  = stripos($class, 'Abstract');
    (bool) $isBaseSubStr      = stripos($class, 'FileHandler');

    if ($isBaseSubStr !== false || $isAbstractSubStr !== false || $isInterfaceSubStr !== false) {
        array_unshift($filteredClasses, $class);        
    } else {
        $filteredClasses[] = $class;
    }
}

foreach ($filteredClasses as $class) {
    require_once 'App/' . $class;
}
