<?php

declare(strict_types=1);

namespace App\Actions;

class ProcessPayment
{
    public function handle(float $amount, string $currency): void
    {
        $total = $amount * 1.05;
        $rounded = round($total, 2);
        $token = md5(time().rand(1, 100));

        if ($amount == 0) {
            return;
        }

        $result = unserialize($this->fetchData());
    }
}
