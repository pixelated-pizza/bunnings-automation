<?php

namespace App\Console\Commands;

use App\Services\OAuthService;
use Illuminate\Console\Command;

class GetToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bunnings:token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets Auth token';

    /**
     * Execute the console command.
     */
     public function handle(OAuthService $service)
    {
        $result = $service->execute();

        if ($result['status']) {
            $this->info('Token fetched successfully!');
            $this->line('Access Token: ' . $result['data']['access_token']);
            $this->line('Expires In: ' . $result['data']['expires_in'] . ' seconds');
            return Command::SUCCESS;
        }

        if (isset($result['code']) && $result['code'] === 401) {
            $this->error('Unauthorized: ' . $result['message']);
            return Command::FAILURE;
        }

        $this->error('Error: ' . $result['message']);
        return Command::FAILURE;
    }
}
