<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UMKM;

class FixUmkmDates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:umkm-dates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Randomize UMKM created_at and tanggal_verifikasi to fix chart data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to fix UMKM dates...');
        
        $umkms = UMKM::all();
        $bar = $this->output->createProgressBar(count($umkms));
        
        $fixedCount = 0;

        foreach ($umkms as $umkm) {
            // Randomize between Jan 1 2024 and Now
            $randomTime = mt_rand(strtotime('2024-01-01'), time());
            
            $umkm->timestamps = false;
            $umkm->created_at = date('Y-m-d H:i:s', $randomTime);
            $umkm->updated_at = date('Y-m-d H:i:s', $randomTime);
            
            if (in_array($umkm->status_verifikasi, ['terverifikasi', 'ditolak'])) {
                // Verification date is 1 to 7 days after creation
                $umkm->tanggal_verifikasi = date('Y-m-d H:i:s', $randomTime + mt_rand(86400, 604800));
            }
            
            $umkm->save();
            $fixedCount++;
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine();
        $this->info('Successfully randomized ' . $fixedCount . ' UMKM records!');
        
        return Command::SUCCESS;
    }
}
