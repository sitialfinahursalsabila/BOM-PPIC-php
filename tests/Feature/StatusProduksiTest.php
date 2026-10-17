<?php

namespace Tests\Feature;

use App\Models\Pengguna;
use App\Models\RencanaProduksi;
use App\Services\KebutuhanBahan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/** LKM 5: core module UC-07 (BR-08, BR-09, UC-11) + hitung kebutuhan bahan (BR-06, BR-10) */
class StatusProduksiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    private function sebagaiProduksi(): self
    {
        return $this->actingAs(Pengguna::find('USR02'));
    }

    public function test_input_valid_draft_ke_diproses(): void
    {
        $this->sebagaiProduksi()->post('/status-produksi/RPP005/ubah', ['status' => 'Diproses'])
            ->assertSessionHas('sukses');
        $this->assertDatabaseHas('rencana_produksi', ['id_rencana' => 'RPP005', 'status' => 'Diproses']);
        $this->assertDatabaseHas('log_status_produksi', [
            'id_log' => 'LOG013', 'id_rencana' => 'RPP005', 'status' => 'Diproses', 'id_pengguna' => 'USR02',
        ]);
    }

    public function test_draft_langsung_ke_selesai_ditolak_br08(): void
    {
        $this->sebagaiProduksi()->post('/status-produksi/RPP005/ubah', ['status' => 'Selesai'])
            ->assertSessionHas('galat_judul');
        $this->assertDatabaseHas('rencana_produksi', ['id_rencana' => 'RPP005', 'status' => 'Draft']);
        $this->assertDatabaseMissing('log_status_produksi', ['id_log' => 'LOG013']);
    }

    public function test_diproses_ke_selesai_bila_bom_lengkap(): void
    {
        $this->sebagaiProduksi()->post('/status-produksi/RPP003/ubah', ['status' => 'Selesai'])
            ->assertSessionHas('sukses');
        $this->assertDatabaseHas('rencana_produksi', ['id_rencana' => 'RPP003', 'status' => 'Selesai']);
        $this->assertDatabaseHas('log_status_produksi', ['id_rencana' => 'RPP003', 'status' => 'Selesai']);
    }

    public function test_selesai_ditolak_bila_bom_belum_lengkap_br09(): void
    {
        // Sub assembly tanpa komponen di bawahnya membuat BOM004 tidak lengkap
        DB::table('detail_bom')->insert([
            'id_detail_bom' => 'DBM099', 'id_bom' => 'BOM004', 'id_item' => 'ITM010',
            'id_induk' => null, 'jumlah' => 1, 'level' => 'Sub Assembly',
        ]);

        $this->sebagaiProduksi()->post('/status-produksi/RPP003/ubah', ['status' => 'Selesai'])
            ->assertSessionHas('galat_judul', 'Status Selesai tidak dapat disimpan.');
        $this->assertDatabaseHas('rencana_produksi', ['id_rencana' => 'RPP003', 'status' => 'Diproses']);
    }

    public function test_status_tidak_dikenal_ditolak_validasi(): void
    {
        $this->sebagaiProduksi()->post('/status-produksi/RPP003/ubah', ['status' => 'Batal'])
            ->assertSessionHasErrors('status');
    }

    public function test_peran_ppic_tidak_boleh_mengubah_status(): void
    {
        $this->actingAs(Pengguna::find('USR01'))
            ->post('/status-produksi/RPP005/ubah', ['status' => 'Diproses'])->assertForbidden();
        $this->assertDatabaseHas('rencana_produksi', ['id_rencana' => 'RPP005', 'status' => 'Draft']);
    }

    public function test_kebutuhan_bahan_rpp006_menandai_cat_kurang_3(): void
    {
        $hasil = (new KebutuhanBahan())->hitung(RencanaProduksi::find('RPP006'));
        $baris = fn (string $kode) => $hasil->first(fn ($b) => $b['item']->kode_item === $kode);

        $this->assertEquals(30, $baris('MDF01')['kebutuhan']);
        $this->assertEquals(120, $baris('SKR01')['kebutuhan']);
        $this->assertEquals(15, $baris('CAT01')['kebutuhan']);
        $this->assertEquals(3, $baris('CAT01')['kurang']);
        $this->assertFalse($baris('CAT01')['cukup']);
        $this->assertTrue($baris('MDF01')['cukup']);
    }
}
