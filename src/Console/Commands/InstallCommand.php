<?php

namespace Meraki\Packages\Auth\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class InstallCommand extends Command
{
    protected $signature = 'meraki-auth:install {--force : Re-run even if already installed}';
    protected $description = 'Install meraki-auth: publish config, run migrations, record state.';

    public function handle(): int
    {
        if (!Schema::hasTable('meraki_meta')) {
            $this->error('meraki_meta table not found. Please run `php artisan meraki:install` first.');
            return self::FAILURE;
        }

        if (!$this->option('force') && $this->alreadyInstalled()) {
            $this->warn('meraki-auth is already installed. Use --force to re-run.');
            return self::SUCCESS;
        }

        $this->call('vendor:publish', ['--tag' => 'meraki-auth-config', '--force' => false]);
        $this->call('vendor:publish', ['--tag' => 'meraki-auth-migrations', '--force' => false]);
        $this->call('migrate', ['--path' => 'database/migrations', '--force' => true]);

        DB::table('meraki_meta')->updateOrInsert(
            ['key' => 'meraki-auth.installed'],
            ['value' => now()->toISOString(), 'updated_at' => now()],
        );

        $this->info('meraki-auth installed successfully.');
        return self::SUCCESS;
    }

    private function alreadyInstalled(): bool
    {
        return DB::table('meraki_meta')->where('key', 'meraki-auth.installed')->exists();
    }
}
