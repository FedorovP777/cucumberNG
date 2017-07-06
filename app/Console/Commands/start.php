<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class start extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'start {--port=7890}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run CucumberNG';

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


        $this->callSilent('key:generate');
        $this->info('Open your browser http://0.0.0.0:' . $this->option('port') . '/' . config('app.keymd5'));
        $this->callSilent("serve", [
            '--host' => '0.0.0.0',
            '--port' => $this->option('port')

        ]);

    }
}
