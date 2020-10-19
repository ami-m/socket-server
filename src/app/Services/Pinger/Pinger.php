<?php


namespace App\Services\Pinger;

/**
 * Class Pinger
 * @package App\Services\Pinger
 *
 * Pinger pings the google dns server and returns the ping statistics
 * it caches the results for from earlier pings for a period of time
 */
class Pinger
{
    private const FRESHNESS = 60; // results stay relevant for FRESHNESS seconds
    private const TARGET = 'dns.google';

    /** @var bool */
    private $isChecking;

    /** @var \DateTime */
    private $lastCheck;

    /** @var string */
    private $lastResult;

    /**
     * Pinger constructor.
     */
    public function __construct()
    {
        $this->isChecking = false;
        $this->lastResult = 'no result';
        $this->lastCheck = null;
    }

    /**
     * @return string
     */
    public function getAvgPing(): string
    {
        if($this->isChecking) {
            $this->waitForResult();
        } elseif(!$this->lastResult || !$this->isResultFresh()) {
            $this->doCheck();
        }

        return $this->lastResult;
    }

    private function waitForResult(): void
    {
        // this is ugly, and in production should be replaced with a better sync with the pinging process
        for($i = 0; $i < 5; $i++) {
            sleep(1);
        }
    }

    /**
     * @return bool
     */
    private function isResultFresh(): bool
    {
        if(!$this->lastCheck) {
            return false;
        }

        $expiryDate = new \DateTime('now');
        $expiryDate->modify(sprintf('-%d seconds', self::FRESHNESS));

        return $expiryDate->getTimestamp() <= $this->lastCheck->getTimestamp();
    }

    private function doCheck(): void
    {
        $this->lastResult = 'no result';

        $this->isChecking = true; // this is naive "locking", swoole enables threading, so better locking is needed.
        exec(sprintf("ping -c 5 %s", self::TARGET), $output, $status);
        if(!is_array($output) || count($output) === 0) {
            $this->isChecking = false;
            return;
        }

        try {
            // obviously this is ugly. in production code I would expect a more robust parsing of the ping result, and better error handling.
            $summaryRow = $output[count($output) - 1];
            preg_match('/(\d+(\.\d+)?)[\D](\d+(\.\d+)?)[\D](\d+(\.\d+)?)[\D](\d+(\.\d+)?)/', $summaryRow, $matches);
            $this->lastResult = $matches[3];
            $this->lastCheck = new \DateTime('now');
        } catch (\Exception $e) {
            $this->lastResult = 'no result';
        } finally {
            $this->isChecking = false;
        }
    }
}