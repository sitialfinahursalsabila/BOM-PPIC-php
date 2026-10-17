<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Pengguna;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** LKM 4: CRUD master item + validasi + proteksi relasi + otorisasi peran */
class ItemCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    private function sebagaiPpic(): self
    {
        return $this->actingAs(Pengguna::find('USR01'));
    }

    private function dataItem(array $ubah = []): array
    {
        return array_merge([
            'kode_item' => 'PKU01', 'deskripsi' => 'Paku 5 cm', 'satuan' => 'pcs',
            'harga_satuan' => 150, 'stok' => 1000,
        ], $ubah);
    }

    public function test_read_daftar_item(): void
    {
        $this->sebagaiPpic()->get('/items')->assertOk()->assertSee('MDF01')->assertSee('RGT01');
    }

    public function test_create_item_baru(): void
    {
        $this->sebagaiPpic()->post('/items', $this->dataItem())->assertRedirect(route('items.index'));
        $this->assertDatabaseHas('item', ['id_item' => 'ITM011', 'kode_item' => 'PKU01']);
    }

    public function test_validasi_kode_item_harus_unik(): void
    {
        $this->sebagaiPpic()->post('/items', $this->dataItem(['kode_item' => 'MDF01']))
            ->assertSessionHasErrors('kode_item');
    }

    public function test_validasi_required_dan_range(): void
    {
        $this->sebagaiPpic()->post('/items', $this->dataItem(['deskripsi' => '', 'stok' => -1, 'harga_satuan' => 'abc']))
            ->assertSessionHasErrors(['deskripsi', 'stok', 'harga_satuan']);
        $this->assertDatabaseMissing('item', ['kode_item' => 'PKU01']);
    }

    public function test_update_item(): void
    {
        $this->sebagaiPpic()->put('/items/ITM010', $this->dataItem(['kode_item' => 'RGT01', 'deskripsi' => 'Rangka besi meja kerja']))
            ->assertRedirect(route('items.index'));
        $this->assertDatabaseHas('item', ['id_item' => 'ITM010', 'deskripsi' => 'Rangka besi meja kerja']);
    }

    public function test_item_yang_dipakai_bom_tidak_dapat_dihapus(): void
    {
        $this->sebagaiPpic()->delete('/items/ITM001')->assertSessionHas('galat');
        $this->assertDatabaseHas('item', ['id_item' => 'ITM001']);
    }

    public function test_item_yang_belum_dipakai_dapat_dihapus(): void
    {
        Item::create(['id_item' => 'ITM011', 'kode_item' => 'PKU01', 'deskripsi' => 'Paku', 'satuan' => 'pcs', 'harga_satuan' => 1, 'stok' => 1]);
        $this->sebagaiPpic()->delete('/items/ITM011')->assertRedirect(route('items.index'));
        $this->assertDatabaseMissing('item', ['id_item' => 'ITM011']);
    }

    public function test_peran_selain_ppic_ditolak(): void
    {
        $this->actingAs(Pengguna::find('USR02'))->get('/items')->assertForbidden();
    }
}
