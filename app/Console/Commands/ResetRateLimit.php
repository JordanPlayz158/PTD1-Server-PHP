<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\RateLimiter;

class ResetRateLimit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rate-limit:reset {key}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resets the rate limit of a certain key';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $key = $this->argument('key');

        RateLimiter::resetAttempts($key);

        return 0;
    }
}
