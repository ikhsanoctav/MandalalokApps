<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class EncryptNikCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:encrypt-nik';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Encrypt existing NIK data in users and pemiliks tables and generate nik_hash';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting NIK encryption process...');

        // Process Pemiliks
        $pemiliks = DB::table('pemiliks')->get();
        $this->info("Found {$pemiliks->count()} records in pemiliks table.");
        $pemiliksCount = 0;
        foreach ($pemiliks as $p) {
            // Check if it's already encrypted (very basic check, encrypted strings usually start with 'ey')
            if ($p->nik && ! str_starts_with($p->nik, 'ey')) {
                DB::table('pemiliks')
                    ->where('id_pemilik', $p->id_pemilik)
                    ->update([
                        'nik' => Crypt::encryptString($p->nik),
                        'nik_hash' => hash('sha256', $p->nik),
                    ]);
                $pemiliksCount++;
            }
        }
        $this->info("Encrypted {$pemiliksCount} records in pemiliks.");

        // Process Users
        $users = DB::table('users')->get();
        $this->info("Found {$users->count()} records in users table.");
        $usersCount = 0;
        foreach ($users as $u) {
            if ($u->nik && ! str_starts_with($u->nik, 'ey')) {
                DB::table('users')
                    ->where('id', $u->id)
                    ->update([
                        'nik' => Crypt::encryptString($u->nik),
                        'nik_hash' => hash('sha256', $u->nik),
                    ]);
                $usersCount++;
            }
        }
        $this->info("Encrypted {$usersCount} records in users.");

        $this->info('NIK encryption process completed successfully.');
    }
}
