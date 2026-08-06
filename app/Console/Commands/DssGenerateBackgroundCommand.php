<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Services\OllamaService;
use App\Models\DssAnalysis;

#[Signature('dss:generate-bg {jobId}')]
#[Description('Generate DSS analysis in the background')]
class DssGenerateBackgroundCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dss:generate-bg {jobId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate DSS analysis in the background';

    /**
     * Execute the console command.
     */
    public function handle(OllamaService $ollamaService)
    {
        $jobId = $this->argument('jobId');
        
        $stats = Cache::get("dss_job_stats_{$jobId}");
        $intervention = Cache::get("dss_job_intervention_{$jobId}");
        $userId = Cache::get("dss_job_user_{$jobId}");

        if (!$stats) {
            $this->error("Stats not found for job {$jobId}");
            return;
        }

        try {
            Log::info("Starting background DSS generation for job {$jobId}");
            
            // Allow up to 15 minutes for generation
            set_time_limit(900);
            
            $reply = $ollamaService->generateDssReport($stats, $intervention);

            if (is_null($reply) || empty($reply)) {
                throw new \Exception('Respons kosong dari AI.');
            }

            $decoded = json_decode($reply, true);
            if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
                // Try to extract JSON from markdown if needed
                $rawText = $reply;
                $rawText = preg_replace('/```json\s*(.*?)\s*```/is', '$1', $rawText);
                $rawText = preg_replace('/```\s*(.*?)\s*```/is', '$1', $rawText);
                $rawText = trim($rawText);
                $decoded = json_decode($rawText, true);

                if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
                    throw new \Exception('Format respons AI rusak atau terpotong (Bukan JSON valid).');
                }
            }

            // Cari tahu apakah response dibungkus oleh object lain
            foreach (['data', 'output', 'response', 'json'] as $key) {
                if (isset($decoded[$key])) {
                    if (is_string($decoded[$key])) {
                        $parsed = json_decode($decoded[$key], true);
                        if (json_last_error() === JSON_ERROR_NONE && is_array($parsed)) {
                            $decoded = array_merge($decoded, $parsed);
                            break;
                        }
                    } elseif (is_array($decoded[$key])) {
                        $decoded = array_merge($decoded, $decoded[$key]);
                        break;
                    }
                }
            }

            if ($intervention) {
                // Ekstrak intervensi_kebijakan jika tidak ada
                if (!isset($decoded['intervensi_kebijakan']) || !is_array($decoded['intervensi_kebijakan'])) {
                    try {
                        $ollamaUrl = rtrim(config('services.ollama.base_url', 'http://host.docker.internal:11434'), '/') . '/api/generate';
                        $prompt = "Ekstrak sasaran program, pendekatan, dan hasil yang diharapkan dari teks intervensi kebijakan berikut:\n\"$intervention\"\n\nKembalikan HANYA JSON murni (tanpa markdown) dengan format tepat seperti ini:\n{\n  \"tujuan_utama\": \"...\",\n  \"sasaran_program\": \"...\",\n  \"pendekatan\": \"...\",\n  \"hasil_diharapkan\": \"...\"\n}";
                        
                        $response = \Illuminate\Support\Facades\Http::timeout(30)->post($ollamaUrl, [
                            'model' => 'qwen2.5:1.5b',
                            'prompt' => $prompt,
                            'stream' => false,
                            'format' => 'json'
                        ]);
                        
                        if ($response->successful()) {
                            $rawText = $response->json('response');
                            if (is_string($rawText)) {
                                $rawText = preg_replace('/```json\s*(.*?)\s*```/is', '$1', $rawText);
                                $rawText = preg_replace('/```\s*(.*?)\s*```/is', '$1', $rawText);
                                $rawText = trim($rawText);
                                
                                $extracted = json_decode($rawText, true);
                                if (json_last_error() === JSON_ERROR_NONE && is_array($extracted)) {
                                    $decoded['intervensi_kebijakan'] = array_merge(['fokus' => $intervention], $extracted);
                                }
                            }
                        }
                    } catch (\Exception $e) {
                        // ignore
                    }

                    if (!isset($decoded['intervensi_kebijakan'])) {
                        $decoded['intervensi_kebijakan'] = [
                            'fokus' => $intervention,
                            'tujuan_utama' => "Tujuan terkait: " . $intervention,
                        ];
                    }
                } else {
                    $decoded['intervensi_kebijakan']['fokus'] = $intervention;
                }
            }

            $record = DssAnalysis::create([
                'user_id' => $userId,
                'analysis_data' => $decoded
            ]);

            $decoded['id'] = $record->id;
            $decoded['created_at'] = $record->created_at->translatedFormat('d M Y, H:i');
            $decoded['creator'] = $record->user ? $record->user->name : 'Sistem';

            $analysis = json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

            Cache::put('dss_analysis', $analysis, 43200);
            Cache::put('dss_analysis_time', now()->format('d M Y, H:i') . ' WIB', 43200);
            Log::info("Background DSS generation completed for job {$jobId}");
        } catch (\Exception $e) {
            Log::error("Background DSS error: " . $e->getMessage());
        } finally {
            Cache::forget("dss_job_stats_{$jobId}");
            Cache::forget("dss_job_intervention_{$jobId}");
            Cache::forget("dss_job_user_{$jobId}");
            Cache::forget('dss_is_generating');
        }
    }
}
