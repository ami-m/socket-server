<?php


namespace App\Services\Menu;


class Menu
{
    public function getGreeting(): string
    {
        $res = "\n---------------\n";
        $res .=  "--- Welcome ---\n";
        $res .=  "---------------\n";
        return $res;
    }

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