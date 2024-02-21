<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ClearAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all cache';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Artisan::call('cache:clear');
        $this->info('Cache  cleared!');
        Artisan::call('config:clear');
        $this->info('Config cleared!');
        Artisan::call('view:clear');
        $this->info('View cleared!');
        Artisan::call('clear-compiled');
        $this->info('Clear-compiled cleared!');
    }
}