<?php

namespace Meraki\Packages\Auth\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UninstallCommand extends Command
{
    protected $signature = 'meraki-auth:uninstall {--drop-tables : Drop auth tables directly instead of rolling back migration}';
    protected $description = 'Uninstall meraki-auth: rollback migrations and clear install state.';

    public function handle(): int
    {
        if (!Schema::hasTable('meraki_meta') || !$this->alreadyInstalled()) {
            $this->warn('meraki-auth is not installed.');
            return self::SUCCESS;
        }

        if ($this->option('drop-tables')) {
            Schema::dropIfExists('sessions');
            Schema::dropIfExists('password_reset_tokens');
            Schema::dropIfExists('users');
        } else {
            $this->call('migrate:rollback', [
                '--path'  => 'database/migrations',
                '--step'  => 1,
                '--force' => true,
            ]);
        }

        DB::table('meraki_meta')->where('key', 'meraki-auth.installed')->delete();

        $this->info('meraki-auth uninstalled.');
        return self::SUCCESS;
    }

    private function alreadyInstalled(): bool
    {
        return DB::table('meraki_meta')->where('key', 'meraki-auth.installed')->exists();
    }
}
