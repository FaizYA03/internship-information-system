<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Labor;
use App\Models\PinjamAlat;
use App\Models\PinjamLabor;
use App\Models\LaporanKerusakan;
use App\Models\Inventaris;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class AdminLabControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create admin lab user
        $this->adminUser = User::factory()->create([
            'role' => 'admin_lab',
            'nama' => 'Admin Lab Test',
            'email' => 'adminlab@test.com',
        ]);
    }

    /** @test */
    public function admin_lab_can_view_dashboard()
    {
        $response = $this->actingAs($this->adminUser)
            ->get(route('lab.admin_new.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('lab.admin.dashboard');
        $response->assertViewHas('stats');
    }

    /** @test */
    public function admin_lab_can_view_laboratories_list()
    {
        Labor::factory()->count(3)->create();

        $response = $this->actingAs($this->adminUser)
            ->get(route('lab.admin_new.laboratorium.index'));

        $response->assertStatus(200);
        $response->assertViewIs('lab.admin.laboratorium.index');
        $response->assertViewHas('laboratories');
    }

    /** @test */
    public function admin_lab_can_approve_equipment_borrowing()
    {
        $labor = Labor::factory()->create();
        $inventaris = Inventaris::factory()->create([
            'labor_id' => $labor->id,
            'jumlah' => 10,
            'status' => 'tersedia'
        ]);
        
       $siswa = User::factory()->create(['role' => 'siswa']);
        
        $pinjam = PinjamAlat::create([
            'user_id' => $siswa->id,
            'inventaris_id' => $inventaris->id,
            'labor_id' => $labor->id,
            'jumlah' => 1,
            'tanggal_pinjam' => now(),
            'jam_pinjam' => '08:00',
            'jam_kembali' => '10:00',
            'keperluan' => 'Praktikum',
            'status' => 'pending'
        ]);

        $response = $this->actingAs($this->adminUser)
            ->post(route('lab.admin_new.peminjaman.internal.approve', $pinjam->id));

        $response->assertRedirect();
        $this->assertDatabaseHas('pinjam_alat', [
            'id' => $pinjam->id,
            'status' => 'approved'
        ]);
    }

    /** @test */
    public function admin_lab_can_reject_equipment_borrowing()
    {
        $labor = Labor::factory()->create();
        $inventaris = Inventaris::factory()->create(['labor_id' => $labor->id]);
        $siswa = User::factory()->create(['role' => 'siswa']);
        
        $pinjam = PinjamAlat::create([
            'user_id' => $siswa->id,
            'inventaris_id' => $inventaris->id,
            'labor_id' => $labor->id,
            'jumlah' => 1,
            'tanggal_pinjam' => now(),
            'jam_pinjam' => '08:00',
            'jam_kembali' => '10:00',
            'keperluan' => 'Praktikum',
            'status' => 'pending'
        ]);

        $response = $this->actingAs($this->adminUser)
            ->post(route('lab.admin_new.peminjaman.internal.reject', $pinjam->id), [
                'reason' => 'Alat sedang diperbaiki'
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('pinjam_alat', [
            'id' => $pinjam->id,
            'status' => 'rejected'
        ]);
    }

    /** @test */
    public function admin_lab_can_approve_room_borrowing()
    {
        $labor = Labor::factory()->create();
        $guru = User::factory()->create(['role' => 'guru']);
        
        $pinjam = PinjamLabor::create([
            'user_id' => $guru->id,
            'labor_id' => $labor->id,
            'tanggal' => now()->addDays(1),
            'waktu' => '08:00-10:00',
            'keperluan' => 'Mengajar',
            'status' => 'pending'
        ]);

        $response = $this->actingAs($this->adminUser)
            ->post(route('lab.admin_new.peminjaman.ruangan.approve', $pinjam->id));

        $response->assertRedirect();
        $this->assertDatabaseHas('pinjam_labor', [
            'id' => $pinjam->id,
            'status' => 'approved'
        ]);
    }

    /** @test */
    public function admin_lab_can_view_damage_reports()
    {
        $labor = Labor::factory()->create();
        $inventaris = Inventaris::factory()->create(['labor_id' => $labor->id]);
        
        LaporanKerusakan::factory()->count(3)->create([
            'inventaris_id' => $inventaris->id,
            'user_id' => $this->adminUser->id
        ]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('lab.admin_new.kerusakan.index'));

        $response->assertStatus(200);
        $response->assertViewIs('lab.admin.kerusakan.index');
        $response->assertViewHas('laporanKerusakan');
    }

    /** @test */
    public function admin_lab_can_update_equipment_condition()
    {
        $labor = Labor::factory()->create();
        $inventaris = Inventaris::factory()->create([
            'labor_id' => $labor->id,
            'kondisi' => 'Rusak Ringan'
        ]);
        
        $laporan = LaporanKerusakan::create([
            'inventaris_id' => $inventaris->id,
            'user_id' => $this->adminUser->id,
            'tanggal_laporan' => now(),
            'deskripsi_kerusakan' => 'Test kerusakan',
            'status_perbaikan' => 'menunggu'
        ]);

        $response = $this->actingAs($this->adminUser)
            ->patch(route('lab.admin_new.kerusakan.update', $laporan->id), [
                'kondisi_baru' => 'Baik',
                'status_perbaikan' => 'selesai',
                'tindakan_perbaikan' => 'Sudah diperbaiki'
            ]);

        $response->assertRedirect();
        $inventaris->refresh();
        $this->assertEquals('Baik', $inventaris->kondisi);
    }

   /** @test */
    public function api_returns_inventaris_by_labor_id()
    {
        $labor = Labor::factory()->create();
        Inventaris::factory()->count(5)->create([
            'labor_id' => $labor->id,
            'status' => 'tersedia',
            'jumlah' => 10
        ]);

        $response = $this->getJson("/api/lab/inventaris/{$labor->id}");

        $response->assertStatus(200);
        $response->assertJsonCount(5);
    }
}
