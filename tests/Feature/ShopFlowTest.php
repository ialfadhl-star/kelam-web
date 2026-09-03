<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Database\Seeders\ProductSeeder;
use Database\Seeders\SettingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Menguji alur toko sebenarnya lewat stack HTTP Laravel (route, controller,
 * cart session, DB). Memakai RefreshDatabase + seed, jadi jalan mandiri dengan
 * `php artisan test` (database uji default).
 */
class ShopFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([SettingSeeder::class, ProductSeeder::class]);
    }

    public function test_storefront_pages_load(): void
    {
        $this->get('/')->assertOk()->assertSee('Bukan');
        $this->get('/katalog')->assertOk();
        $this->get('/katalog?kategori=Outerwear')->assertOk();
        $this->get('/katalog?warna=Olive&ukuran=M')->assertOk();
        $this->get('/tentang')->assertOk();
        $this->get('/kontak')->assertOk();

        $product = Product::first();
        $this->get('/produk/' . $product->slug)->assertOk()->assertSee($product->name);
    }

    public function test_full_cart_to_order_flow(): void
    {
        $variant = ProductVariant::where('stock', '>', 3)->with('product')->first();
        $this->assertNotNull($variant, 'Butuh varian dengan stok > 3');
        $stockBefore = $variant->stock;

        // Tambah ke keranjang
        $this->postJson('/keranjang/tambah', ['variant_id' => $variant->id, 'quantity' => 2])
            ->assertOk()
            ->assertJson(['ok' => true, 'count' => 2]);

        // Halaman keranjang menampilkan item
        $this->get('/keranjang')->assertOk()->assertSee($variant->product->name);

        // Halaman checkout tampil
        $this->get('/checkout')->assertOk()->assertSee('Metode Pembayaran');

        // Buat pesanan (COD)
        $this->post('/checkout', [
            'customer_name' => 'Uji Otomatis',
            'customer_phone' => '081234567890',
            'customer_email' => 'uji@example.com',
            'shipping_address' => 'Jl. Uji No. 1',
            'shipping_city' => 'Jakarta',
            'shipping_postal_code' => '12345',
            'payment_method' => 'cod',
        ])->assertRedirect();

        $order = Order::where('customer_name', 'Uji Otomatis')->latest()->first();
        $this->assertNotNull($order, 'Order harus terbuat');
        $this->assertEquals('cod', $order->payment_method);
        $this->assertEquals('menunggu_pembayaran', $order->status);
        $this->assertEquals($variant->product->price * 2, $order->subtotal);
        $this->assertCount(1, $order->items);

        // Stok berkurang
        $this->assertEquals($stockBefore - 2, $variant->fresh()->stock, 'Stok harus berkurang 2');

        // Halaman konfirmasi tampil + instruksi COD
        $this->get('/pesanan/' . $order->order_number)
            ->assertOk()
            ->assertSee($order->order_number)
            ->assertSee('COD');
    }

    public function test_checkout_validation_rejects_incomplete(): void
    {
        $variant = ProductVariant::where('stock', '>', 0)->first();
        $this->postJson('/keranjang/tambah', ['variant_id' => $variant->id, 'quantity' => 1]);

        $this->post('/checkout', ['customer_name' => 'Uji'])
            ->assertSessionHasErrors(['customer_phone', 'shipping_address', 'shipping_city', 'payment_method']);
    }

    public function test_admin_auth_and_crud(): void
    {
        $admin = User::create([
            'name' => 'Uji Admin',
            'email' => 'uji-admin@example.com',
            'password' => Hash::make('rahasia-uji-123'),
        ]);

        // Guest diarahkan ke login admin (bukan 500)
        $this->get('/admin')->assertRedirect(route('admin.login'));

        // Login salah ditolak
        $this->post('/admin/login', ['email' => 'uji-admin@example.com', 'password' => 'salah'])
            ->assertSessionHasErrors();

        // Login benar
        $this->post('/admin/login', ['email' => 'uji-admin@example.com', 'password' => 'rahasia-uji-123']);
        $this->assertAuthenticated();

        // Password tersimpan sebagai hash, bukan plaintext
        $this->assertNotEquals('rahasia-uji-123', $admin->fresh()->password);
        $this->assertTrue(Hash::check('rahasia-uji-123', $admin->fresh()->password));

        // Dashboard bisa diakses
        $this->actingAs($admin)->get('/admin')->assertOk();

        // Buat produk baru
        $this->actingAs($admin)->post('/admin/products', [
            'name' => 'Uji Produk Otomatis',
            'category' => 'T-Shirt',
            'price' => 99000,
            'gradient_from' => '#111111',
            'gradient_to' => '#000000',
            'is_active' => '1',
            'variants' => [
                ['color' => 'Onyx Black', 'color_hex' => '#0A0A0A', 'size' => 'M', 'stock' => 5],
            ],
        ])->assertRedirect(route('admin.products.index'));

        $created = Product::where('name', 'Uji Produk Otomatis')->first();
        $this->assertNotNull($created);
        $this->assertCount(1, $created->variants);

        // Update status pesanan
        $order = Order::create([
            'order_number' => Order::generateNumber(),
            'customer_name' => 'Uji', 'customer_phone' => '08123',
            'shipping_address' => 'x', 'shipping_city' => 'x',
            'payment_method' => 'transfer_bank', 'subtotal' => 1000, 'shipping_cost' => 0, 'total' => 1000,
        ]);
        $this->actingAs($admin)->patch('/admin/orders/' . $order->order_number, [
            'status' => 'dikirim', 'courier' => 'JNE', 'tracking_number' => 'JNE123',
        ])->assertRedirect();
        $this->assertEquals('dikirim', $order->fresh()->status);
    }

    public function test_login_rate_limited(): void
    {
        // throttle:5,1 -> percobaan ke-6 harus 429
        for ($i = 0; $i < 5; $i++) {
            $this->post('/admin/login', ['email' => 'nobody@example.com', 'password' => 'x']);
        }
        $this->post('/admin/login', ['email' => 'nobody@example.com', 'password' => 'x'])
            ->assertStatus(429);
    }
}
