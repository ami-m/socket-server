<?php


namespace App\Services\Menu;

/**
 * Class Menu
 * @package App\Services\Menu
 *
 * Responsible for user interface rendering
 */
class Menu
{
    /**
     * @return string
     */
    public function getGreeting(): string
    {
        $res = "\n---------------\n";
        $res .=  "--- Welcome ---\n";
        $res .=  "---------------\n";
        return $res;
    }

    /**
     * @return string
     */
    public function getOptions(): string
    {
        $res = "\n";
        $res .=  "[1] disk space\n";
        $res .=   "[2] ping avg on 8.8.8.8\n";
        $res .=   "[3] google top results\n";
        $res .=   "[4] exit\n\n";
        $res .=   "Enter: ";
        return $res;
    }
}