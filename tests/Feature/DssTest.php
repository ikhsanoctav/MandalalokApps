<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\DssAnalysis;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DssTest extends TestCase
{
    use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();

        config(['services.n8n.dss_async' => false]);

        // Ensure roles exist in the database for the test
        $roles = ['super_admin', 'admin_kecamatan', 'operator_lapangan', 'pelaku_umkm'];
        foreach ($roles as $roleName) {
            if (!Role::where('name', $roleName)->exists()) {
                Role::create(['name' => $roleName, 'guard_name' => 'web']);
            }
        }
    }

    /**
     * Test guests cannot access DSS routes.
     */
    public function test_guest_cannot_access_dss(): void
    {
        $response = $this->get(route('dss.index'));
        $response->assertRedirect('/login');

        $responsePost = $this->postJson(route('dss.generate'));
        $responsePost->assertStatus(401); // Unauthorized
    }

    /**
     * Test unauthorized roles (e.g., pelaku_umkm) cannot access DSS routes.
     */
    public function test_unauthorized_role_cannot_access_dss(): void
    {
        $user = User::factory()->create();
        $user->assignRole('pelaku_umkm');

        $response = $this->actingAs($user)->get(route('dss.index'));
        $response->assertStatus(403);

        $responsePost = $this->actingAs($user)->postJson(route('dss.generate'));
        $responsePost->assertStatus(403);
    }

    /**
     * Test authorized roles can access DSS index.
     */
    public function test_authorized_roles_can_access_dss_index(): void
    {
        $roles = ['super_admin', 'admin_kecamatan'];

        foreach ($roles as $roleName) {
            $user = User::factory()->create();
            $user->assignRole($roleName);

            $response = $this->actingAs($user)->get(route('dss.index'));
            $response->assertStatus(200)
                ->assertViewIs('dss.index');
        }
    }

    public function test_dss_index_loads_latest_analysis_when_cache_is_empty(): void
    {
        $user = User::factory()->create();
        $user->assignRole('super_admin');

        Cache::forget('dss_analysis');
        Cache::forget('dss_analysis_time');

        \App\Models\DssAnalysis::create([
            'user_id' => $user->id,
            'analysis_data' => [
                'ringkasan_eksekutif' => 'Ringkasan dari database.',
                'indikator_kunci' => []
            ]
        ]);

        $response = $this->actingAs($user)->get(route('dss.index'));

        $response->assertStatus(200)
            ->assertViewHas('cachedAnalysis', function ($analysis) {
                return is_string($analysis)
                    && str_contains($analysis, 'Ringkasan dari database.');
            });

        $this->assertTrue(Cache::has('dss_analysis'));
    }

    public function test_dss_status_hydrates_latest_analysis_when_cache_is_empty(): void
    {
        $user = User::factory()->create();
        $user->assignRole('super_admin');

        Cache::forget('dss_analysis');
        Cache::forget('dss_analysis_time');

        \App\Models\DssAnalysis::create([
            'user_id' => $user->id,
            'analysis_data' => [
                'ringkasan_eksekutif' => 'Ringkasan status dari database.',
                'indikator_kunci' => []
            ]
        ]);

        $response = $this->actingAs($user)->get(route('dss.status'));

        $response->assertStatus(200)
            ->assertJson([
                'is_generating' => false,
                'has_analysis' => true,
            ]);
    }

    /**
     * Test successful DSS generation.
     */
    public function test_dss_successful_generation(): void
    {
        $user = User::factory()->create();
        $user->assignRole('super_admin');

        $mockJson = json_encode([
            'ringkasan_eksekutif' => 'Ringkasan eksekutif tes.',
            'analisis_swot' => [
                'kekuatan' => ['Kekuatan 1'],
                'kelemahan' => ['Kelemahan 1'],
                'peluang' => ['Peluang 1'],
                'ancaman' => ['Ancaman 1']
            ],
            'prioritas_intervensi' => [
                [
                    'skor_urgensi' => 9,
                    'area' => 'Pasir Impun',
                    'alasan' => 'Banyak UMKM kritis',
                    'aksi_konkret' => 'Pelatihan pemasaran'
                ]
            ],
            'analisis_kategori' => [
                'kategori_dominan' => 'Makanan',
                'insight_ekosistem' => 'Kategori makanan sangat berkembang.',
                'potensi_kolaborasi' => 'Festival kuliner'
            ],
            'analisis_wilayah' => [
                'insight_ketimpangan' => 'Ketimpangan wilayah timur.',
                'strategi_pemerataan' => 'Bantuan modal khusus'
            ],
            'analisis_pertumbuhan' => [
                'tren_umum' => 'Pertumbuhan positif.',
                'proyeksi' => 'Meningkat 5% bulan depan'
            ],
            'analisis_verifikasi' => [
                'insight' => 'Proses verifikasi lancar.',
                'rekomendasi_operasional' => 'Sertifikasi produk cepat'
            ],
            'program_kerja_kategori' => [
                [
                    'kategori' => 'Makanan',
                    'usulan_program' => 'Pelatihan Kuliner Kreatif',
                    'alasan' => 'Meningkatkan nilai jual makanan lokal',
                    'skor_kebutuhan_pelatihan' => 85
                ]
            ],
            'rekomendasi_kebijakan' => [
                'jangka_pendek_1_3_bulan' => ['Langkah 1'],
                'jangka_menengah_3_6_bulan' => ['Langkah 2'],
                'jangka_panjang_6_12_bulan' => ['Langkah 3']
            ],
            'catatan_metodologi' => 'Berdasarkan data sampel.'
        ]);

        $mockOllama = \Mockery::mock(\App\Services\OllamaService::class);
        $mockOllama->shouldReceive('generateDssReport')->once()->andReturn($mockJson);
        $this->app->instance(\App\Services\OllamaService::class, $mockOllama);

        Cache::forget('dss_analysis');
        Cache::forget('dss_analysis_time');

        $response = $this->actingAs($user)->postJson(route('dss.generate'), [
            'force' => 'true'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true
            ]);

        $this->assertDatabaseHas('dss_analyses', [
            'user_id' => $user->id
        ]);
    }

    public function test_dss_async_generation_dispatches_n8n_job(): void
    {
        config([
            'services.n8n.dss_async' => true,
            'services.n8n.dss_webhook_url' => 'https://n8n.test/webhook/dss-api',
            'services.n8n.dss_callback_url' => 'https://app.test/api/dss/callback',
        ]);

        Http::fake([
            'https://n8n.test/webhook/dss-api' => Http::response(['accepted' => true], 202),
        ]);

        $user = User::factory()->create();
        $user->assignRole('super_admin');

        Cache::forget('dss_analysis');
        Cache::forget('dss_analysis_time');
        Cache::forget('dss_is_generating');

        $response = $this->actingAs($user)->postJson(route('dss.generate'), [
            'force' => 'true'
        ]);

        $response->assertStatus(202)
            ->assertJson([
                'success' => true,
                'processing' => true,
            ]);

        $jobId = $response->json('job_id');

        $this->assertNotEmpty($jobId);
        $this->assertTrue(Cache::has("dss_job_token_{$jobId}"));
        $this->assertSame($user->id, Cache::get("dss_job_user_{$jobId}"));
        $this->assertTrue(Cache::has("dss_job_stats_{$jobId}"));
        $this->assertTrue(Cache::get('dss_is_generating'));

        Http::assertSent(function ($request) use ($jobId) {
            return $request->url() === 'https://n8n.test/webhook/dss-api'
                && $request['job_id'] === $jobId
                && $request['callback_url'] === 'https://app.test/api/dss/callback'
                && !empty($request['callback_token'])
                && !empty($request['decision_focus'])
                && !empty($request['stats_json']);
        });
    }

    public function test_dss_callback_stores_async_analysis_and_clears_job_cache(): void
    {
        $user = User::factory()->create();
        $user->assignRole('super_admin');

        $jobId = 'job-test-1';
        $token = 'callback-token-test';
        $stats = [
            'Ringkasan Sistem' => [
                'Total Seluruh UMKM Terdaftar' => 10,
                'UMKM Terverifikasi' => 7,
                'UMKM Ditolak' => 1,
                'Tingkat Verifikasi (%)' => 70,
                'Rata-rata Tenaga Kerja per UMKM' => 2.5,
            ],
            'Distribusi Berdasarkan Kategori Usaha' => ['Mikro' => 8],
            'Distribusi Berdasarkan Sektor Usaha' => ['Kuliner' => 6],
            'Distribusi Berdasarkan Kelurahan' => ['Pasir Impun' => 6],
            'Kelurahan Kritis (UMKM < 5)' => ['Cisaranten Bina Harapan'],
        ];

        Cache::put("dss_job_token_{$jobId}", $token, 900);
        Cache::put("dss_job_user_{$jobId}", $user->id, 900);
        Cache::put("dss_job_stats_{$jobId}", $stats, 900);
        Cache::put('dss_is_generating', true, 300);

        $output = json_encode([
            'ringkasan_eksekutif' => 'Ringkasan async dari n8n.',
            'analisis_swot' => [
                'kekuatan' => ['Kekuatan 1'],
                'kelemahan' => ['Kelemahan 1'],
                'peluang' => ['Peluang 1'],
                'ancaman' => ['Ancaman 1'],
            ],
        ]);

        $response = $this->postJson(route('api.dss.callback'), [
            'job_id' => $jobId,
            'token' => $token,
            'output' => $output,
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('dss_analyses', [
            'user_id' => $user->id,
        ]);

        $analysis = DssAnalysis::latest()->first();

        $this->assertSame('Ringkasan async dari n8n.', $analysis->analysis_data['ringkasan_eksekutif']);
        $this->assertSame(70, $analysis->analysis_data['indikator_kunci']['tingkat_verifikasi_persen']);
        $this->assertFalse(Cache::has("dss_job_token_{$jobId}"));
        $this->assertFalse(Cache::has("dss_job_user_{$jobId}"));
        $this->assertFalse(Cache::has("dss_job_stats_{$jobId}"));
        $this->assertFalse(Cache::has('dss_is_generating'));
        $this->assertTrue(Cache::has('dss_analysis'));
    }

    /**
     * Test DSS generation failure handles errors gracefully.
     */
    public function test_dss_handles_failure_gracefully(): void
    {
        $user = User::factory()->create();
        $user->assignRole('super_admin');

        $mockOllama = \Mockery::mock(\App\Services\OllamaService::class);
        $mockOllama->shouldReceive('generateDssReport')->once()->andThrow(new \Exception('Ollama connection failed'));
        $this->app->instance(\App\Services\OllamaService::class, $mockOllama);

        Cache::forget('dss_analysis');
        Cache::forget('dss_analysis_time');

        $response = $this->actingAs($user)->postJson(route('dss.generate'), [
            'force' => 'true'
        ]);

        $response->assertStatus(500)
            ->assertJson([
                'success' => false,
                'message' => 'Gagal memuat analisis kebijakan AI. Detail: Ollama connection failed'
            ]);
    }

    /**
     * Test history endpoints (load and delete).
     */
    public function test_dss_history_endpoints(): void
    {
        $user = User::factory()->create();
        $user->assignRole('super_admin');

        $analysisData = [
            'ringkasan_eksekutif' => 'Ringkasan tes history.',
            'indikator_kunci' => []
        ];

        // Create dummy analysis record
        $analysis = \App\Models\DssAnalysis::create([
            'user_id' => $user->id,
            'analysis_data' => $analysisData
        ]);

        // 1. Test load history
        $responseLoad = $this->actingAs($user)->get(route('dss.history.show', $analysis->id));
        $responseLoad->assertStatus(200)
            ->assertJson([
                'success' => true,
                'creator' => $user->name,
                'id' => $analysis->id
            ]);

        // 2. Test delete history
        $responseDelete = $this->actingAs($user)->delete(route('dss.history.destroy', $analysis->id));
        $responseDelete->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Riwayat analisis berhasil dihapus.'
            ]);

        $this->assertDatabaseMissing('dss_analyses', [
            'id' => $analysis->id
        ]);
    }
}
