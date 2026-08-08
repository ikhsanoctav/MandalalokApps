<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FixNikCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fix-nik {--key= : The old APP_KEY used when encrypting the data} {--plain : Reset NIK to plain unencrypted text}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix NIK encryption by decrypting with old APP_KEY and re-encrypting with current APP_KEY (or setting to plaintext)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $oldKeyInput = $this->option('key');
        $toPlain = $this->option('plain');

        $encrypters = [];

        // Current encrypter
        try {
            $encrypters[] = Crypt::getFacadeRoot();
        } catch (\Exception $e) {
            // Ignore
        }

        // If old key provided
        if ($oldKeyInput) {
            try {
                $rawKey = $oldKeyInput;
                if (Str::startsWith($rawKey, 'base64:')) {
                    $rawKey = base64_decode(substr($rawKey, 7));
                }
                $encrypters[] = new Encrypter($rawKey, config('app.cipher', 'AES-256-CBC'));
            } catch (\Exception $e) {
                $this->error("Invalid old key format: {$e->getMessage()}");
            }
        }

        // Add known default local key as fallback encrypter
        $defaultLocalKey = 'base64:FQJmZdVFuBOZLEfz2lCoJmMa6fMwkvEE8SJUpy7/qmY=';
        try {
            $rawKey = base64_decode(substr($defaultLocalKey, 7));
            $encrypters[] = new Encrypter($rawKey, 'AES-256-CBC');
        } catch (\Exception $e) {
            // Ignore
        }

        $this->info('Starting NIK repair process...');

        // 1. Fix Pemiliks
        $pemiliks = DB::table('pemiliks')->get();
        $fixedPemiliks = 0;

        foreach ($pemiliks as $p) {
            if (!$p->nik) continue;

            $plainNik = null;

            // If it starts with 'ey', it is an encrypted payload
            if (Str::startsWith($p->nik, 'ey')) {
                foreach ($encrypters as $enc) {
                    try {
                        $plainNik = $enc->decryptString($p->nik);
                        if ($plainNik) break;
                    } catch (\Exception $e) {
                        // Continue trying next encrypter
                    }
                }
            } else {
                // Already plain NIK
                $plainNik = $p->nik;
            }

            if ($plainNik) {
                $newNik = $toPlain ? $plainNik : Crypt::encryptString($plainNik);
                $newHash = hash('sha256', $plainNik);

                DB::table('pemiliks')
                    ->where('id_pemilik', $p->id_pemilik)
                    ->update([
                        'nik' => $newNik,
                        'nik_hash' => $newHash,
                    ]);
                $fixedPemiliks++;
            } else {
                $this->warn("Could not decrypt NIK for Pemilik ID {$p->id_pemilik} (NIK starts with: " . substr($p->nik, 0, 15) . '...)');
            }
        }

        $this->info("Successfully fixed {$fixedPemiliks} records in pemiliks table.");

        // 2. Fix Users
        $users = DB::table('users')->get();
        $fixedUsers = 0;

        foreach ($users as $u) {
            if (!$u->nik) continue;

            $plainNik = null;

            if (Str::startsWith($u->nik, 'ey')) {
                foreach ($encrypters as $enc) {
                    try {
                        $plainNik = $enc->decryptString($u->nik);
                        if ($plainNik) break;
                    } catch (\Exception $e) {
                        // Continue trying next encrypter
                    }
                }
            } else {
                $plainNik = $u->nik;
            }

            if ($plainNik) {
                $newNik = $toPlain ? $plainNik : Crypt::encryptString($plainNik);
                $newHash = hash('sha256', $plainNik);

                DB::table('users')
                    ->where('id', $u->id)
                    ->update([
                        'nik' => $newNik,
                        'nik_hash' => $newHash,
                    ]);
                $fixedUsers++;
            } else {
                $this->warn("Could not decrypt NIK for User ID {$u->id}");
            }
        }

        $this->info("Successfully fixed {$fixedUsers} records in users table.");
        $this->info('NIK repair process finished.');
    }
}
