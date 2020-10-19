<?php


namespace App\Services\Pinger;


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

    public function getAvgPing(): string
    {
        if($this->isChecking) {
            $this->waitForResult();
        } elseif(!$this->lastResult || !$this->isResultFresh()) {
            $this->doCheck();
        }

        return $this->lastResult;
    }

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
        $this->isChecking = true;
        exec(sprintf("ping -c 5 %s", self::TARGET), $output, $status);
        if(!is_array($output) || count($output) === 0) {
            $this->isChecking = false;
            return;
        }

        try {
            // obviously this is ugly, and not fit for production.
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

    private function waitForResult(): void
    {
        // this is ugly, and in production should be replaced with a better sync with the pinging process
        for($i = 0; $i < 5; $i++) {
            sleep(1);
        }
    }
}