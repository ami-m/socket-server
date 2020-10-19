<?php


namespace App;


use App\Services\FS\FS;
use App\Services\Menu\Menu;
use App\Services\Pinger\Pinger;
use App\Services\Searcher\Searcher;

/**
 * Class Application
 * @package App
 *
 * Each worker process holds one instance of this class.
 * This class is responsible for understanding client commands, and dispatching them to the appropriate services.
 */
class Application
{
    private const CMD_HD_SPACE = '1';
    private const CMD_DNS_PING = '2';
    private const CMD_TOP_RESULTS = '3';
    private const CMD_EXIT = '4';

    /** @var Menu */
    private $menu;

    /** @var FS */
    private $fs;

    /** @var Pinger */
    private $pinger;

    /** @var Searcher */
    private $searcher;

    /**
     * Application constructor.
     * @param Menu $menu
     * @param FS $fs
     * @param Pinger $pinger
     */
    public function __construct(Menu $menu, FS $fs, Pinger $pinger)
    {
        $this->menu = $menu;
        $this->fs = $fs;
        $this->pinger = $pinger;
    }


    /**
     * @return string
     */
    public function getGreeting(): string
    {
        return $this->menu->getGreeting();
    }

    /**
     * @return string
     */
    public function getMenu(): string
    {
        return $this->menu->getOptions();
    }


    /**
     * @param $server
     * @param string $fd
     * @param string $cmd
     */
    public function runMenuCommand($server, string $fd, string $cmd): void
    {
        $cmd = trim($cmd);
        switch($cmd) {
            case self::CMD_HD_SPACE:
                $server->send($fd, $this->fs->getHDSize());
                break;

            case self::CMD_DNS_PING:
                $server->send($fd, $this->pinger->getAvgPing());
                break;

            case self::CMD_TOP_RESULTS:
                $server->send($fd, $this->searcher->doSearch(''));
                break;

            case self::CMD_EXIT:
                $server->close($fd);
                break;

            default:
                $server->send($fd, sprintf('unknown command [%s]', $cmd));
        }
    }

}