<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class GenerateToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'larabbs:generate-token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '快速为用户生成 token';

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
        $userId = $this->ask('请输入用户id');

        $user = User::find($userId);

        if (!$user) {
            $this->error('用户不存在');
        }

        $ttl = 365 * 24 * 60;

        $this->info(auth('api')->setTTL($ttl)->login($user));


    }
}
