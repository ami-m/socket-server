<?php


namespace App\Services\FS;


class FS
{
    public function getHDSize(): string
    {
        $total = round(disk_total_space("/") / 1024 / 1024 / 1024);
        $free = round(disk_free_space("/") / 1024 / 1024 / 1024);
        return sprintf('Using %d / %d GB', $free, $total);
    }
}