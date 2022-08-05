<?php

class Singleton
{
    private static $instance;

    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new FileHandlerFacade();
        }

        return self::$instance;
    }
}
