<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use phpseclib\Crypt\RSA;

class GenerateJwtKeys extends Command
{

    protected const PRIVATE_KEY_NAME = 'JWT_PRIVATE_KEY';
    protected const PUBLIC_KEY_NAME = 'JWT_PUBLIC_KEY';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jwt:keys {--l|length=1024} {--s|show}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "
        Generate RSA public and private keys for JWT and store to .env file.
        WARNING: The command doesn't check if keys are setted already or not.
    ";

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
        $rsa = new RSA;

        $data = $rsa->createKey((int) $this->option('length'));

        if ($this->option('show')) {
            $this->info(var_export([
                'private_key' => $data['privatekey'],
                'public_key' => $data['publickey'],
            ], true));
        }

        $this->putKeys($data['privatekey'], $data['publickey']);
    }

    protected function putKeys($private_key, $public_key)
    {
        file_put_contents(base_path('.env'), "\n\n".static::PRIVATE_KEY_NAME."=\"$private_key\"", FILE_APPEND);
        file_put_contents(base_path('.env'), "\n\n".static::PUBLIC_KEY_NAME."=\"$public_key\"", FILE_APPEND);
    }
}
