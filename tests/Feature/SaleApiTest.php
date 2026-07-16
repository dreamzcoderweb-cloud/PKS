<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Dealer;
use App\Models\Sale;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Unit;
use App\Models\AlternateUnit;
use App\Models\Stock;
use App\Models\StockMovement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SaleApiTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $user;
    protected $tokenAdmin;
    protected $tokenUser;
    protected $branch;
    protected $dealer;
    protected $vehicle;
    protected $unit;
    protected $alternateUnit;
    protected $stock1;
    protected $stock2;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        // Create standard relationships dependencies
        $this->branch = Branch::create([
            'name' => 'Main Test Branch',
            'price' => 100.00,
            'status' => 1
        ]);

        $this->admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        $this->user = User::create([
            'name' => 'Regular User',
            'email' => 'user@test.com',
            'password' => bcrypt('password'),
            'role' => 'user'
        ]);

        $this->tokenAdmin = $this->admin->createToken('token')->plainTextToken;
        $this->tokenUser = $this->user->createToken('token')->plainTextToken;

        $this->dealer = Dealer::create([
            'dealer_id' => 'dlr-123',
            'dealer_code' => 'DLR_001',
            'branch_id' => $this->branch->branch_id,
            'name' => 'Dealer One',
            'business_name' => 'Dealer Corp',
            'contact_number' => '1234567890',
            'address' => 'Test Address',
            'status' => 1,
            'created_by' => $this->admin->id
        ]);

        $this->vehicle = Vehicle::create([
            'vehicle_type' => 'lorry',
            'name' => 'Vehicle One',
            'status' => 1
        ]);

        $this->unit = Unit::create([
            'unit' => 'KG'
        ]);

        $this->alternateUnit = AlternateUnit::create([
            'alter_unit' => 'G'
        ]);

        $this->stock1 = Stock::create([
            'stock_id' => 'stk-001',
            'brand_name' => 'Brand A',
            'stock_name' => 'Stock X',
            'lott_number' => 'L-01',
            'units' => 100,
            'mt' => 10.0,
            'stock_code' => 'STK001',
            'branch_id' => $this->branch->branch_id,
            'unit_id' => $this->unit->unit_id,
            'alter_unit_id' => $this->alternateUnit->alter_unit_id,
            'unit_value' => 1.0,
            'alter_unit_value' => 1000.0,
            'created_by' => $this->admin->id
        ]);

        $this->stock2 = Stock::create([
            'stock_id' => 'stk-002',
            'brand_name' => 'Brand B',
            'stock_name' => 'Stock Y',
            'lott_number' => 'L-02',
            'units' => 50,
            'mt' => 5.0,
            'stock_code' => 'STK002',
            'branch_id' => $this->branch->branch_id,
            'unit_id' => $this->unit->unit_id,
            'alter_unit_id' => $this->alternateUnit->alter_unit_id,
            'unit_value' => 1.0,
            'alter_unit_value' => 1000.0,
            'created_by' => $this->admin->id
        ]);
    }

    /**
     * Test admin sale CRUD operations.
     */
    public function test_admin_sale_crud_operations()
    {
        $img1 = UploadedFile::fake()->create('img1.jpg', 100, 'image/jpeg');
        $img2 = UploadedFile::fake()->create('img2.jpg', 100, 'image/jpeg');

        $saleData = [
            'branch_id' => $this->branch->branch_id,
            'dealer_id' => $this->dealer->id,
            'vehicle_id' => $this->vehicle->vehicle_id,
            'invoice_number' => 'INV-001',
            'driver_name' => 'John Driver',
            'driver_number' => 'DRV-999',
            'sale_date' => '2026-07-16 12:00:00',
            'sale_images' => [$img1, $img2],
            'details' => [
                [
                    'stock_id' => $this->stock1->id,
                    'lot_number' => 'LOT-SALE-1-A',
                    'unit_value' => 10.00,
                    'unit_id' => $this->unit->unit_id,
                    'alternate_unit_value' => 1.00,
                    'alternate_unit_id' => $this->alternateUnit->alter_unit_id
                ]
            ]
        ];

        // 1. Create Sale
        $responseCreate = $this->postJson('/api/admin/sales', $saleData, [
            'Authorization' => 'Bearer ' . $this->tokenAdmin
        ]);

        $responseCreate->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Sale created successfully.');

        $saleId = $responseCreate->json('data.id');
        $this->assertNotNull($saleId);

        // Verify database records
        $this->assertDatabaseHas('sales', [
            'id' => $saleId,
            'invoice_number' => 'INV-001',
            'driver_name' => 'John Driver',
            'driver_number' => 'DRV-999',
            'created_by' => $this->admin->id
        ]);

        $this->assertDatabaseHas('sale_details', [
            'sale_id' => $saleId,
            'stock_id' => $this->stock1->id,
            'lot_number' => 'LOT-SALE-1-A',
            'unit_value' => 10.00,
        ]);

        // Verify stock deduction
        $this->stock1 = $this->stock1->fresh();
        $this->assertEquals(90, $this->stock1->units);
        $this->assertEquals(9.0, $this->stock1->mt);

        // Verify stock movement log
        $this->assertDatabaseHas('stock_movements', [
            'stock_id' => $this->stock1->id,
            'sale_id' => $saleId,
            'quantity' => -10.00,
            'unit' => 'KG',
            'movement_type' => 'sale',
            'user_id' => $this->admin->id,
        ]);

        // 2. List Sales
        $responseList = $this->getJson('/api/admin/sales', [
            'Authorization' => 'Bearer ' . $this->tokenAdmin
        ]);

        $responseList->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.dealer_name', 'Dealer One')
            ->assertJsonPath('data.0.vehicle_number', 'Vehicle One')
            ->assertJsonPath('data.0.total_items', 1);

        // 3. Show Sale
        $responseShow = $this->getJson('/api/admin/sales/' . $saleId, [
            'Authorization' => 'Bearer ' . $this->tokenAdmin
        ]);

        $responseShow->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.driver_name', 'John Driver')
            ->assertJsonCount(1, 'data.details');

        // 4. Update Sale
        $newImg1 = UploadedFile::fake()->create('newimg1.jpg', 100, 'image/jpeg');
        $newImg2 = UploadedFile::fake()->create('newimg2.jpg', 100, 'image/jpeg');

        $updateData = [
            'branch_id' => $this->branch->branch_id,
            'dealer_id' => $this->dealer->id,
            'vehicle_id' => $this->vehicle->vehicle_id,
            'invoice_number' => 'INV-UPDATED',
            'driver_name' => 'Jane Driver',
            'driver_number' => 'DRV-888',
            'sale_date' => '2026-07-16 13:00:00',
            'sale_images' => [$newImg1, $newImg2],
            'details' => [
                [
                    'stock_id' => $this->stock2->id,
                    'lot_number' => 'LOT-UPDATED-B',
                    'unit_value' => 20.00,
                    'unit_id' => $this->unit->unit_id,
                    'alternate_unit_value' => 2.00,
                    'alternate_unit_id' => $this->alternateUnit->alter_unit_id
                ]
            ]
        ];

        $responseUpdate = $this->postJson('/api/admin/sales/' . $saleId, $updateData, [
            'Authorization' => 'Bearer ' . $this->tokenAdmin
        ]);

        $responseUpdate->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Sale updated successfully.')
            ->assertJsonPath('data.invoice_number', 'INV-UPDATED')
            ->assertJsonPath('data.driver_name', 'Jane Driver')
            ->assertJsonCount(1, 'data.details');

        // Verify stock is restored on the old item
        $this->stock1 = $this->stock1->fresh();
        $this->assertEquals(100, $this->stock1->units); // Restored
        $this->assertEquals(10.0, $this->stock1->mt);

        // Verify stock is deducted on the new item
        $this->stock2 = $this->stock2->fresh();
        $this->assertEquals(30, $this->stock2->units); // Deducted
        $this->assertEquals(3.0, $this->stock2->mt);

        // 5. Delete Sale (Soft Delete)
        $responseDelete = $this->deleteJson('/api/admin/sales/' . $saleId, [], [
            'Authorization' => 'Bearer ' . $this->tokenAdmin
        ]);

        $responseDelete->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Sale deleted successfully.');

        // Verify soft deleted in database (deleted_at is set)
        $this->assertSoftDeleted('sales', ['id' => $saleId]);

        // Verify stock is restored on deletion
        $this->stock2 = $this->stock2->fresh();
        $this->assertEquals(50, $this->stock2->units); // Restored
        $this->assertEquals(5.0, $this->stock2->mt);

        // Verify stock movements deleted
        $this->assertDatabaseMissing('stock_movements', ['sale_id' => $saleId]);
    }

    /**
     * Test user sale operations.
     */
    public function test_user_sale_operations()
    {
        $img1 = UploadedFile::fake()->create('img1.jpg', 100, 'image/jpeg');
        $img2 = UploadedFile::fake()->create('img2.jpg', 100, 'image/jpeg');

        $saleData = [
            'branch_id' => $this->branch->branch_id,
            'dealer_id' => $this->dealer->id,
            'vehicle_id' => $this->vehicle->vehicle_id,
            'invoice_number' => 'INV-USER-001',
            'driver_name' => 'John Driver',
            'driver_number' => 'DRV-111',
            'sale_date' => '2026-07-16 12:00:00',
            'sale_images' => [$img1, $img2],
            'details' => [
                [
                    'stock_id' => $this->stock1->id,
                    'lot_number' => 'LOT-USER-1-A',
                    'unit_value' => 5.00,
                    'unit_id' => $this->unit->unit_id,
                    'alternate_unit_value' => 0.50,
                    'alternate_unit_id' => $this->alternateUnit->alter_unit_id
                ]
            ]
        ];

        // 1. Create Sale
        $responseCreate = $this->postJson('/api/user/sales', $saleData, [
            'Authorization' => 'Bearer ' . $this->tokenUser
        ]);

        $responseCreate->assertStatus(201)
            ->assertJsonPath('success', true);

        $saleId = $responseCreate->json('data.id');

        // 2. List Sales
        $responseList = $this->getJson('/api/user/sales', [
            'Authorization' => 'Bearer ' . $this->tokenUser
        ]);

        $responseList->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonCount(1, 'data');

        // 3. Show Sale
        $responseShow = $this->getJson('/api/user/sales/' . $saleId, [
            'Authorization' => 'Bearer ' . $this->tokenUser
        ]);

        $responseShow->assertStatus(200)
            ->assertJsonPath('success', true);

        // 4. Update Sale is NOT allowed for User App
        $responseUpdate = $this->putJson('/api/user/sales/' . $saleId, [
            'invoice_number' => 'NEW-INV'
        ], [
            'Authorization' => 'Bearer ' . $this->tokenUser
        ]);

        $responseUpdate->assertStatus(405);

        // 5. Force Delete Sale
        $responseDelete = $this->deleteJson('/api/user/sales/' . $saleId . '?force=true', [], [
            'Authorization' => 'Bearer ' . $this->tokenUser
        ]);

        $responseDelete->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Sale deleted successfully.');

        // Verify permanent deletion from DB
        $this->assertDatabaseMissing('sales', ['id' => $saleId]);
    }

    /**
     * Test validation rules and stock insufficiency checks.
     */
    public function test_sale_validation_errors()
    {
        // 1. Image count validator (fewer than 2 images)
        $img1 = UploadedFile::fake()->create('img1.jpg', 100, 'image/jpeg');
        $responseImageCountError = $this->postJson('/api/admin/sales', [
            'branch_id' => $this->branch->branch_id,
            'dealer_id' => $this->dealer->id,
            'vehicle_id' => $this->vehicle->vehicle_id,
            'invoice_number' => 'INV-VAL',
            'driver_name' => 'Driver',
            'driver_number' => 'DRV-VAL',
            'sale_date' => '2026-07-16 12:00:00',
            'sale_images' => [$img1], // Only 1 image
            'details' => [
                [
                    'stock_id' => $this->stock1->id,
                    'lot_number' => 'LOT-VAL-1-A',
                    'unit_value' => 10,
                    'unit_id' => $this->unit->unit_id,
                ]
            ]
        ], [
            'Authorization' => 'Bearer ' . $this->tokenAdmin
        ]);

        $responseImageCountError->assertStatus(422)
            ->assertJsonValidationErrors(['sale_images']);

        // 2. Insufficient stock error
        $img2 = UploadedFile::fake()->create('img2.jpg', 100, 'image/jpeg');
        $responseStockError = $this->postJson('/api/admin/sales', [
            'branch_id' => $this->branch->branch_id,
            'dealer_id' => $this->dealer->id,
            'vehicle_id' => $this->vehicle->vehicle_id,
            'invoice_number' => 'INV-VAL',
            'driver_name' => 'Driver',
            'driver_number' => 'DRV-VAL',
            'sale_date' => '2026-07-16 12:00:00',
            'sale_images' => [$img1, $img2],
            'details' => [
                [
                    'stock_id' => $this->stock1->id,
                    'lot_number' => 'LOT-VAL-1-A',
                    'unit_value' => 200, // stock only has 100
                    'unit_id' => $this->unit->unit_id,
                ]
            ]
        ], [
            'Authorization' => 'Bearer ' . $this->tokenAdmin
        ]);

        $responseStockError->assertStatus(422)
            ->assertJsonValidationErrors(['details']);
    }
}
