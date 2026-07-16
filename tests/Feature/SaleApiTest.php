<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Dealer;
use App\Models\Sale;
use App\Models\Transporter;
use App\Models\User;
use App\Models\Vehicle;
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
    protected $transporter;
    protected $vehicle;

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
            'created_by' => $this->admin->id
        ]);

        $this->transporter = Transporter::create([
            'name' => 'Transporter One',
            'branch_id' => $this->branch->branch_id
        ]);

        $this->vehicle = Vehicle::create([
            'vehicle_type' => 'lorry',
            'name' => 'Vehicle One',
            'status' => 1
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
            'lot_number' => 'LOT-SALE-1',
            'transporter_id' => $this->transporter->transporter_id,
            'vehicle_id' => $this->vehicle->vehicle_id,
            'invoice_number' => 'INV-001',
            'driver_number' => 'DRV-999',
            'sale_images' => [$img1, $img2],
            'details' => [
                [
                    'brand_name' => 'Brand A',
                    'stock_name' => 'Stock X',
                    'lot_number' => 'LOT-SALE-1-A',
                    'unit_value' => 50.00,
                    'unit_type' => 'KG',
                    'alter_unit_value' => 50000.00,
                    'alter_unit_type' => 'G'
                ],
                [
                    'brand_name' => 'Brand B',
                    'stock_name' => 'Stock Y',
                    'lot_number' => 'LOT-SALE-1-B',
                    'unit_value' => 20.50,
                    'unit_type' => 'KG',
                    'alter_unit_value' => 20500.00,
                    'alter_unit_type' => 'G'
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
            'lot_number' => 'LOT-SALE-1',
            'invoice_number' => 'INV-001',
            'driver_number' => 'DRV-999',
            'created_by' => $this->admin->id
        ]);

        $this->assertDatabaseHas('sale_details', [
            'sale_id' => $saleId,
            'brand_name' => 'Brand A',
            'stock_name' => 'Stock X',
            'lot_number' => 'LOT-SALE-1-A',
        ]);

        // 2. List Sales
        $responseList = $this->getJson('/api/admin/sales', [
            'Authorization' => 'Bearer ' . $this->tokenAdmin
        ]);

        $responseList->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonCount(1, 'data');

        // 3. Show Sale
        $responseShow = $this->getJson('/api/admin/sales/' . $saleId, [
            'Authorization' => 'Bearer ' . $this->tokenAdmin
        ]);

        $responseShow->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.lot_number', 'LOT-SALE-1')
            ->assertJsonCount(2, 'data.details');

        // 4. Update Sale (via direct fallback POST route to support multipart/form-data upload)
        $newImg1 = UploadedFile::fake()->create('newimg1.jpg', 100, 'image/jpeg');
        $newImg2 = UploadedFile::fake()->create('newimg2.jpg', 100, 'image/jpeg');
        $newImg3 = UploadedFile::fake()->create('newimg3.jpg', 100, 'image/jpeg');

        $updateData = [
            'branch_id' => $this->branch->branch_id,
            'dealer_id' => $this->dealer->id,
            'lot_number' => 'LOT-SALE-UPDATED',
            'transporter_id' => $this->transporter->transporter_id,
            'vehicle_id' => $this->vehicle->vehicle_id,
            'invoice_number' => 'INV-UPDATED',
            'driver_number' => 'DRV-888',
            'sale_images' => [$newImg1, $newImg2, $newImg3],
            'details' => [
                [
                    'brand_name' => 'Brand Updated',
                    'stock_name' => 'Stock Updated',
                    'lot_number' => 'LOT-UPDATED-A',
                    'unit_value' => 75.00,
                    'unit_type' => 'KG',
                    'alter_unit_value' => 75000.00,
                    'alter_unit_type' => 'G'
                ]
            ]
        ];

        $responseUpdate = $this->postJson('/api/admin/sales/' . $saleId, $updateData, [
            'Authorization' => 'Bearer ' . $this->tokenAdmin
        ]);

        $responseUpdate->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Sale updated successfully.')
            ->assertJsonPath('data.lot_number', 'LOT-SALE-UPDATED')
            ->assertJsonPath('data.invoice_number', 'INV-UPDATED')
            ->assertJsonCount(1, 'data.details');

        // Verify updated database records
        $this->assertDatabaseHas('sales', [
            'id' => $saleId,
            'lot_number' => 'LOT-SALE-UPDATED',
            'invoice_number' => 'INV-UPDATED',
            'driver_number' => 'DRV-888'
        ]);

        // 5. Delete Sale
        $responseDelete = $this->deleteJson('/api/admin/sales/' . $saleId, [], [
            'Authorization' => 'Bearer ' . $this->tokenAdmin
        ]);

        $responseDelete->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Sale deleted successfully.');

        $this->assertDatabaseMissing('sales', ['id' => $saleId]);
        $this->assertDatabaseMissing('sale_details', ['sale_id' => $saleId]);
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
            'lot_number' => 'LOT-USER-1',
            'transporter_id' => $this->transporter->transporter_id,
            'vehicle_id' => $this->vehicle->vehicle_id,
            'invoice_number' => 'INV-USER-001',
            'driver_number' => 'DRV-111',
            'sale_images' => [$img1, $img2],
            'details' => [
                [
                    'brand_name' => 'Brand User',
                    'stock_name' => 'Stock User',
                    'lot_number' => 'LOT-USER-1-A',
                    'unit_value' => 30.00,
                    'unit_type' => 'KG',
                    'alter_unit_value' => 30000.00,
                    'alter_unit_type' => 'G'
                ]
            ]
        ];

        // 1. Create Sale
        $responseCreate = $this->postJson('/api/user/sales', $saleData, [
            'Authorization' => 'Bearer ' . $this->tokenUser
        ]);

        $responseCreate->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Sale created successfully.');

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
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.lot_number', 'LOT-USER-1');

        // 4. Update Sale is NOT allowed for User App (returns 405 Method Not Allowed)
        $responseUpdate = $this->putJson('/api/user/sales/' . $saleId, [
            'lot_number' => 'NEW-LOT'
        ], [
            'Authorization' => 'Bearer ' . $this->tokenUser
        ]);

        $responseUpdate->assertStatus(405);

        // 5. Delete Sale
        $responseDelete = $this->deleteJson('/api/user/sales/' . $saleId, [], [
            'Authorization' => 'Bearer ' . $this->tokenUser
        ]);

        $responseDelete->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Sale deleted successfully.');

        $this->assertDatabaseMissing('sales', ['id' => $saleId]);
    }

    /**
     * Test validation rules for sale creation.
     */
    public function test_sale_validation_errors()
    {
        // 1. Image count validator (fewer than 2 images)
        $img1 = UploadedFile::fake()->create('img1.jpg', 100, 'image/jpeg');
        $responseImageCountError = $this->postJson('/api/admin/sales', [
            'branch_id' => $this->branch->branch_id,
            'dealer_id' => $this->dealer->id,
            'lot_number' => 'LOT-VAL-1',
            'transporter_id' => $this->transporter->transporter_id,
            'vehicle_id' => $this->vehicle->vehicle_id,
            'invoice_number' => 'INV-VAL',
            'driver_number' => 'DRV-VAL',
            'sale_images' => [$img1], // Only 1 image (minimum is 2)
            'details' => [
                [
                    'brand_name' => 'Brand',
                    'stock_name' => 'Stock',
                    'lot_number' => 'LOT-VAL-1-A',
                    'unit_value' => 10,
                    'unit_type' => 'KG',
                    'alter_unit_value' => 10000,
                    'alter_unit_type' => 'G'
                ]
            ]
        ], [
            'Authorization' => 'Bearer ' . $this->tokenAdmin
        ]);

        $responseImageCountError->assertStatus(422)
            ->assertJsonValidationErrors(['sale_images']);

        // 2. Missing required master fields
        $responseMissingFields = $this->postJson('/api/admin/sales', [], [
            'Authorization' => 'Bearer ' . $this->tokenAdmin
        ]);

        $responseMissingFields->assertStatus(422)
            ->assertJsonValidationErrors([
                'branch_id',
                'dealer_id',
                'lot_number',
                'transporter_id',
                'vehicle_id',
                'invoice_number',
                'driver_number',
                'sale_images',
                'details'
            ]);
    }
}
