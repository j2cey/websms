<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class RunPhp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'runphp';//php artisan run [file path]

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run php file with system env support.';

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
     * @return int
     */
    public function handle()
    {
        $file = $this->argument('php_file');
        if (is_file($file)) {
            include $file;
        } else {
            $this->error("file '{$file}' not found!");
        }
    }
    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('php_file', InputArgument::REQUIRED, 'The php file to run with Laputa.'),
        );
    }
}
