<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PksApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test admin registration.
     */
    public function test_admin_can_register()
    {
        $branch = \App\Models\Branch::create([
            'name' => 'Main Branch',
            'price' => 150.00,
            'status' => 1
        ]);

        $response = $this->postJson('/api/admin/register', [
            'name' => 'Admin User',
            'email' => 'admin@pks.com',
            'mobile_number' => '1234567890',
            'password' => 'secret123',
            'branch_id' => $branch->branch_id,
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Admin registered successfully.',
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'data' => ['id', 'name', 'email', 'role', 'mobile_number', 'branch_id', 'status']
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'admin@pks.com',
            'role' => 'admin',
            'mobile_number' => '1234567890',
            'branch_id' => $branch->branch_id,
            'status' => 1,
        ]);
    }

    /**
     * Test login validation and role checks.
     */
    public function test_login_authenticates_and_verifies_role()
    {
        // Create an admin
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@pks.com',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        // Attempt logging in as admin through user login endpoint
        $response = $this->postJson('/api/user/login', [
            'email' => 'admin@pks.com',
            'password' => 'password'
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Validation failed.'
            ]);

        // Attempt logging in as admin through admin login endpoint
        $response2 = $this->postJson('/api/admin/login', [
            'email' => 'admin@pks.com',
            'password' => 'password'
        ]);

        $response2->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Login successful.'
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'user' => ['id', 'name', 'email', 'role'],
                    'token'
                ]
            ]);
    }

    /**
     * Test profile retrieval and logout.
     */
    public function test_profile_and_logout()
    {
        $user = User::create([
            'name' => 'User',
            'email' => 'user@pks.com',
            'password' => bcrypt('password'),
            'role' => 'user'
        ]);

        $token = $user->createToken('test_token')->plainTextToken;

        // Get Profile
        $response = $this->getJson('/api/user/profile', [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Profile details retrieved.'
            ]);

        // Logout
        $responseLogout = $this->postJson('/api/user/logout', [], [
            'Authorization' => 'Bearer ' . $token
        ]);

        $responseLogout->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Logged out successfully.'
            ]);

        // Forget guards to clear cached auth state in testing context
        $this->app['auth']->forgetGuards();

        // Try getting profile again (unauthenticated)
        $responseBlocked = $this->getJson('/api/user/profile', [
            'Authorization' => 'Bearer ' . $token
        ]);

        $responseBlocked->assertStatus(401);
    }

    /**
     * Test stock operations and authorization.
     */
    public function test_stock_operations_and_permissions()
    {
        $admin = User::create(['name' => 'Admin', 'email' => 'admin@pks.com', 'password' => bcrypt('password'), 'role' => 'admin']);
        $user1 = User::create(['name' => 'User One', 'email' => 'user1@pks.com', 'password' => bcrypt('password'), 'role' => 'user']);
        $user2 = User::create(['name' => 'User Two', 'email' => 'user2@pks.com', 'password' => bcrypt('password'), 'role' => 'user']);

        $tokenAdmin = $admin->createToken('token')->plainTextToken;
        $tokenUser1 = $user1->createToken('token')->plainTextToken;
        $tokenUser2 = $user2->createToken('token')->plainTextToken;

        // User 1 creates a stock
        $responseCreate = $this->postJson('/api/user/stocks', [
            'brand_name' => 'Brand A',
            'stock_name' => 'Product X',
            'lott_number' => 'L-123',
            'units' => 10,
            'mt' => 1.5
        ], ['Authorization' => 'Bearer ' . $tokenUser1]);

        $responseCreate->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Stock created successfully.'
            ]);

        $stock = Stock::first();
        $this->assertNotNull($stock->stock_code);
        $this->assertEquals('STOCK_001', $stock->stock_code);

        $this->app['auth']->forgetGuards();

        // User 1 views own stocks
        $responseListOwn = $this->getJson('/api/user/stocks', ['Authorization' => 'Bearer ' . $tokenUser1]);
        $responseListOwn->assertStatus(200);
        $this->assertCount(1, $responseListOwn->json('data'));

        $this->app['auth']->forgetGuards();

        // User 2 views stocks (should be empty for User 2)
        $responseListOther = $this->getJson('/api/user/stocks', ['Authorization' => 'Bearer ' . $tokenUser2]);
        $responseListOther->assertStatus(200);
        $this->assertCount(0, $responseListOther->json('data'));

        $this->app['auth']->forgetGuards();

        // User 2 tries to view User 1's stock details (should be forbidden/unauthorized)
        $responseDetailOther = $this->getJson('/api/user/stocks/' . $stock->id, ['Authorization' => 'Bearer ' . $tokenUser2]);
        $responseDetailOther->assertStatus(403);

        $this->app['auth']->forgetGuards();

        // Admin views all stocks
        $responseAdminList = $this->getJson('/api/admin/stocks', ['Authorization' => 'Bearer ' . $tokenAdmin]);
        $responseAdminList->assertStatus(200);
        $this->assertCount(1, $responseAdminList->json('data'));

        $this->app['auth']->forgetGuards();

        // Admin updates User 1's stock
        $responseUpdate = $this->putJson('/api/admin/stocks/' . $stock->id, [
            'brand_name' => 'Brand A Updated',
            'units' => 20
        ], ['Authorization' => 'Bearer ' . $tokenAdmin]);

        $responseUpdate->assertStatus(200)
            ->assertJsonPath('data.brand_name', 'Brand A Updated')
            ->assertJsonPath('data.units', 20);

        $this->app['auth']->forgetGuards();

        // Admin deletes the stock
        $responseDelete = $this->deleteJson('/api/admin/stocks/' . $stock->id, [], ['Authorization' => 'Bearer ' . $tokenAdmin]);
        $responseDelete->assertStatus(200);
        $this->assertEquals(0, Stock::count());
    }

    /**
     * Test customer operations and authorization.
     */
    public function test_customer_operations_and_permissions()
    {
        $admin = User::create(['name' => 'Admin', 'email' => 'admin@pks.com', 'password' => bcrypt('password'), 'role' => 'admin']);
        $user1 = User::create(['name' => 'User One', 'email' => 'user1@pks.com', 'password' => bcrypt('password'), 'role' => 'user']);
        $user2 = User::create(['name' => 'User Two', 'email' => 'user2@pks.com', 'password' => bcrypt('password'), 'role' => 'user']);

        $tokenAdmin = $admin->createToken('token')->plainTextToken;
        $tokenUser1 = $user1->createToken('token')->plainTextToken;
        $tokenUser2 = $user2->createToken('token')->plainTextToken;

        // User 1 creates a customer
        $responseCreate = $this->postJson('/api/user/customers', [
            'name' => 'John Doe',
            'business' => 'Acme Corp',
            'mobile' => '9876543210',
            'location' => 'New York',
            'address' => '123 Wall St',
            'gst_number' => 'GSTIN12345'
        ], ['Authorization' => 'Bearer ' . $tokenUser1]);

        $responseCreate->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Customer created successfully.'
            ]);

        $customer = Customer::first();
        $this->assertNotNull($customer->customer_code);
        $this->assertEquals('CUSTOMER_001', $customer->customer_code);

        $this->app['auth']->forgetGuards();

        // User 1 views own customers
        $responseListOwn = $this->getJson('/api/user/customers', ['Authorization' => 'Bearer ' . $tokenUser1]);
        $responseListOwn->assertStatus(200);
        $this->assertCount(1, $responseListOwn->json('data'));

        $this->app['auth']->forgetGuards();

        // User 2 views customers (should be empty)
        $responseListOther = $this->getJson('/api/user/customers', ['Authorization' => 'Bearer ' . $tokenUser2]);
        $responseListOther->assertStatus(200);
        $this->assertCount(0, $responseListOther->json('data'));

        $this->app['auth']->forgetGuards();

        // User 2 tries to view User 1's customer details (should be forbidden)
        $responseDetailOther = $this->getJson('/api/user/customers/' . $customer->id, ['Authorization' => 'Bearer ' . $tokenUser2]);
        $responseDetailOther->assertStatus(403);

        $this->app['auth']->forgetGuards();

        // Admin views all customers
        $responseAdminList = $this->getJson('/api/admin/customers', ['Authorization' => 'Bearer ' . $tokenAdmin]);
        $responseAdminList->assertStatus(200);
        $this->assertCount(1, $responseAdminList->json('data'));

        $this->app['auth']->forgetGuards();

        // Admin updates User 1's customer
        $responseUpdate = $this->putJson('/api/admin/customers/' . $customer->id, [
            'name' => 'John Doe Updated',
            'location' => 'Los Angeles'
        ], ['Authorization' => 'Bearer ' . $tokenAdmin]);

        $responseUpdate->assertStatus(200)
            ->assertJsonPath('data.name', 'John Doe Updated')
            ->assertJsonPath('data.location', 'Los Angeles');

        $this->app['auth']->forgetGuards();

        // Admin deletes the customer
        $responseDelete = $this->deleteJson('/api/admin/customers/' . $customer->id, [], ['Authorization' => 'Bearer ' . $tokenAdmin]);
        $responseDelete->assertStatus(200);
        $this->assertEquals(0, Customer::count());
    }

    /**
     * Test sequential stock code generation.
     */
    public function test_sequential_stock_codes()
    {
        $admin = User::create(['name' => 'Admin', 'email' => 'admin@pks.com', 'password' => bcrypt('password'), 'role' => 'admin']);
        $user = User::create(['name' => 'User', 'email' => 'user@pks.com', 'password' => bcrypt('password'), 'role' => 'user']);

        $tokenAdmin = $admin->createToken('token')->plainTextToken;
        $tokenUser = $user->createToken('token')->plainTextToken;

        // User creates first stock
        $response1 = $this->postJson('/api/user/stocks', [
            'brand_name' => 'Brand', 'stock_name' => 'Product 1', 'lott_number' => 'L-01', 'units' => 10, 'mt' => 1.0
        ], ['Authorization' => 'Bearer ' . $tokenUser]);
        $response1->assertStatus(201);
        $this->assertEquals('STOCK_001', $response1->json('data.stock_code'));

        // User creates second stock
        $response2 = $this->postJson('/api/user/stocks', [
            'brand_name' => 'Brand', 'stock_name' => 'Product 2', 'lott_number' => 'L-02', 'units' => 20, 'mt' => 2.0
        ], ['Authorization' => 'Bearer ' . $tokenUser]);
        $response2->assertStatus(201);
        $this->assertEquals('STOCK_002', $response2->json('data.stock_code'));

        $this->app['auth']->forgetGuards();

        // Admin creates first stock
        $responseAdmin1 = $this->postJson('/api/admin/stocks', [
            'brand_name' => 'Brand', 'stock_name' => 'Product A1', 'lott_number' => 'L-A1', 'units' => 30, 'mt' => 3.0
        ], ['Authorization' => 'Bearer ' . $tokenAdmin]);
        $responseAdmin1->assertStatus(201);
        $this->assertEquals('STOCK_A001', $responseAdmin1->json('data.stock_code'));

        // Admin creates second stock
        $responseAdmin2 = $this->postJson('/api/admin/stocks', [
            'brand_name' => 'Brand', 'stock_name' => 'Product A2', 'lott_number' => 'L-A2', 'units' => 40, 'mt' => 4.0
        ], ['Authorization' => 'Bearer ' . $tokenAdmin]);
        $responseAdmin2->assertStatus(201);
        $this->assertEquals('STOCK_A002', $responseAdmin2->json('data.stock_code'));
    }

    /**
     * Test sequential customer code generation.
     */
    public function test_sequential_customer_codes()
    {
        $admin = User::create(['name' => 'Admin', 'email' => 'admin@pks.com', 'password' => bcrypt('password'), 'role' => 'admin']);
        $user = User::create(['name' => 'User', 'email' => 'user@pks.com', 'password' => bcrypt('password'), 'role' => 'user']);

        $tokenAdmin = $admin->createToken('token')->plainTextToken;
        $tokenUser = $user->createToken('token')->plainTextToken;

        // User creates first customer
        $response1 = $this->postJson('/api/user/customers', [
            'name' => 'Customer 1', 'business' => 'Biz 1', 'mobile' => '1234567890', 'location' => 'Loc 1'
        ], ['Authorization' => 'Bearer ' . $tokenUser]);
        $response1->assertStatus(201);
        $this->assertEquals('CUSTOMER_001', $response1->json('data.customer_code'));

        // User creates second customer
        $response2 = $this->postJson('/api/user/customers', [
            'name' => 'Customer 2', 'business' => 'Biz 2', 'mobile' => '1234567891', 'location' => 'Loc 2'
        ], ['Authorization' => 'Bearer ' . $tokenUser]);
        $response2->assertStatus(201);
        $this->assertEquals('CUSTOMER_002', $response2->json('data.customer_code'));

        $this->app['auth']->forgetGuards();

        // Admin creates first customer
        $responseAdmin1 = $this->postJson('/api/admin/customers', [
            'name' => 'Customer A1', 'business' => 'Biz A1', 'mobile' => '1234567892', 'location' => 'Loc A1'
        ], ['Authorization' => 'Bearer ' . $tokenAdmin]);
        $responseAdmin1->assertStatus(201);
        $this->assertEquals('CUSTOMER_A001', $responseAdmin1->json('data.customer_code'));

        // Admin creates second customer
        $responseAdmin2 = $this->postJson('/api/admin/customers', [
            'name' => 'Customer A2', 'business' => 'Biz A2', 'mobile' => '1234567893', 'location' => 'Loc A2'
        ], ['Authorization' => 'Bearer ' . $tokenAdmin]);
        $responseAdmin2->assertStatus(201);
        $this->assertEquals('CUSTOMER_A002', $responseAdmin2->json('data.customer_code'));
    }

    /**
     * Test branch CRUD operations for both Admin and User applications.
     */
    public function test_branch_crud_operations()
    {
        $admin = User::create(['name' => 'Admin', 'email' => 'admin@pks.com', 'password' => bcrypt('password'), 'role' => 'admin']);
        $user = User::create(['name' => 'User', 'email' => 'user@pks.com', 'password' => bcrypt('password'), 'role' => 'user']);

        $tokenAdmin = $admin->createToken('token')->plainTextToken;
        $tokenUser = $user->createToken('token')->plainTextToken;

        // --- Admin App CRUD ---

        // 1. Create Branch (Admin)
        $responseCreate = $this->postJson('/api/admin/branches', [
            'name' => 'Admin Branch',
            'price' => 250.00
        ], ['Authorization' => 'Bearer ' . $tokenAdmin]);

        $responseCreate->assertStatus(201)
            ->assertJsonPath('data.name', 'Admin Branch')
            ->assertJsonPath('data.price', '250.00')
            ->assertJsonPath('data.status', 1);

        $branchId = $responseCreate->json('data.branch_id');

        $this->app['auth']->forgetGuards();

        // 2. Read Branches (Admin)
        $responseIndex = $this->getJson('/api/admin/branches', ['Authorization' => 'Bearer ' . $tokenAdmin]);
        $responseIndex->assertStatus(200);
        $this->assertCount(1, $responseIndex->json('data'));

        $this->app['auth']->forgetGuards();

        // 3. Show Details (Admin)
        $responseShow = $this->getJson('/api/admin/branches/' . $branchId, ['Authorization' => 'Bearer ' . $tokenAdmin]);
        $responseShow->assertStatus(200)
            ->assertJsonPath('data.name', 'Admin Branch');

        $this->app['auth']->forgetGuards();

        // 4. Update Branch (Admin)
        $responseUpdate = $this->putJson('/api/admin/branches/' . $branchId, [
            'name' => 'Admin Branch Updated',
            'price' => 300.50,
            'status' => 0
        ], ['Authorization' => 'Bearer ' . $tokenAdmin]);

        $responseUpdate->assertStatus(200)
            ->assertJsonPath('data.name', 'Admin Branch Updated')
            ->assertJsonPath('data.price', '300.50')
            ->assertJsonPath('data.status', 0);

        $this->app['auth']->forgetGuards();

        // --- User App CRUD ---

        // 1. Create Branch (User)
        $responseUserCreate = $this->postJson('/api/user/branches', [
            'name' => 'User Branch',
            'price' => 120.00
        ], ['Authorization' => 'Bearer ' . $tokenUser]);

        $responseUserCreate->assertStatus(201)
            ->assertJsonPath('data.name', 'User Branch')
            ->assertJsonPath('data.price', '120.00')
            ->assertJsonPath('data.status', 1);

        $userBranchId = $responseUserCreate->json('data.branch_id');

        $this->app['auth']->forgetGuards();

        // 2. Read Branches (User)
        $responseUserIndex = $this->getJson('/api/user/branches', ['Authorization' => 'Bearer ' . $tokenUser]);
        $responseUserIndex->assertStatus(200);
        $this->assertCount(2, $responseUserIndex->json('data'));

        $this->app['auth']->forgetGuards();

        // 3. Update Branch (User)
        $responseUserUpdate = $this->putJson('/api/user/branches/' . $userBranchId, [
            'name' => 'User Branch Updated',
            'price' => 140.00
        ], ['Authorization' => 'Bearer ' . $tokenUser]);

        $responseUserUpdate->assertStatus(200)
            ->assertJsonPath('data.name', 'User Branch Updated')
            ->assertJsonPath('data.price', '140.00');

        $this->app['auth']->forgetGuards();

        // 4. Delete Branch (User)
        $responseUserDelete = $this->deleteJson('/api/user/branches/' . $userBranchId, [], ['Authorization' => 'Bearer ' . $tokenUser]);
        $responseUserDelete->assertStatus(200);

        $this->app['auth']->forgetGuards();

        // 5. Delete Branch (Admin)
        $responseDelete = $this->deleteJson('/api/admin/branches/' . $branchId, [], ['Authorization' => 'Bearer ' . $tokenAdmin]);
        $responseDelete->assertStatus(200);

        $this->assertEquals(0, \App\Models\Branch::count());
    }

    /**
     * Test user registered from Admin app with role 'user' can login to the User app.
     */
    public function test_user_registration_via_admin_app_can_login_to_user_app()
    {
        $branch = \App\Models\Branch::create([
            'name' => 'Main Branch',
            'price' => 100.00,
            'status' => 1
        ]);

        // Register a user with role 'user' via Admin App register endpoint
        $responseRegister = $this->postJson('/api/admin/register', [
            'name' => 'Registered User',
            'email' => 'reguser@pks.com',
            'mobile_number' => '9999999999',
            'password' => 'password123',
            'branch_id' => $branch->branch_id,
            'role' => 'user'
        ]);

        $responseRegister->assertStatus(201)
            ->assertJsonPath('data.role', 'user');

        $this->assertDatabaseHas('users', [
            'email' => 'reguser@pks.com',
            'role' => 'user'
        ]);

        $this->app['auth']->forgetGuards();

        // Login through the User app login endpoint
        $responseLogin = $this->postJson('/api/user/login', [
            'email' => 'reguser@pks.com',
            'password' => 'password123'
        ]);

        $responseLogin->assertStatus(200)
            ->assertJsonPath('data.user.role', 'user')
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'user' => ['id', 'name', 'email', 'role'],
                    'token'
                ]
            ]);
    }

    /**
     * Test vehicle CRUD operations for user and admin.
     */
    public function test_vehicle_crud_operations()
    {
        $admin = User::create(['name' => 'Admin', 'email' => 'admin@pks.com', 'password' => bcrypt('password'), 'role' => 'admin']);
        $user = User::create(['name' => 'User', 'email' => 'user@pks.com', 'password' => bcrypt('password'), 'role' => 'user']);

        $tokenAdmin = $admin->createToken('token')->plainTextToken;
        $tokenUser = $user->createToken('token')->plainTextToken;

        // --- Store Lorry Vehicle ---
        $responseCreateLorry = $this->postJson('/api/user/vehicles', [
            'vehicle_type' => 'lorry',
            'vehicle_number' => 'MH-12-AB-1234',
        ], ['Authorization' => 'Bearer ' . $tokenUser]);

        $responseCreateLorry->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Vehicle created successfully.'
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'data' => ['vehicle_id', 'vehicle_type', 'vehicle_number', 'driver_number', 'status']
            ]);

        $this->assertDatabaseHas('vehicles', [
            'vehicle_type' => 'lorry',
            'vehicle_number' => 'MH-12-AB-1234',
            'driver_number' => null,
            'status' => 1
        ]);

        $vehicleLorryId = $responseCreateLorry->json('data.vehicle_id');

        $this->app['auth']->forgetGuards();

        // --- Store Local Vehicle ---
        $responseCreateLocal = $this->postJson('/api/user/vehicles', [
            'vehicle_type' => 'local',
            'driver_number' => '9876543210',
        ], ['Authorization' => 'Bearer ' . $tokenUser]);

        $responseCreateLocal->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Vehicle created successfully.'
            ]);

        $this->assertDatabaseHas('vehicles', [
            'vehicle_type' => 'local',
            'driver_number' => '9876543210',
            'vehicle_number' => null,
            'status' => 1
        ]);

        $vehicleLocalId = $responseCreateLocal->json('data.vehicle_id');

        $this->app['auth']->forgetGuards();

        // --- Validation failure: Lorry missing vehicle_number ---
        $responseFailLorry = $this->postJson('/api/user/vehicles', [
            'vehicle_type' => 'lorry',
        ], ['Authorization' => 'Bearer ' . $tokenUser]);

        $responseFailLorry->assertStatus(422);

        // --- Validation failure: Local missing driver_number ---
        $responseFailLocal = $this->postJson('/api/user/vehicles', [
            'vehicle_type' => 'local',
        ], ['Authorization' => 'Bearer ' . $tokenUser]);

        $responseFailLocal->assertStatus(422);

        $this->app['auth']->forgetGuards();

        // --- List Vehicles (User App) ---
        $responseListUser = $this->getJson('/api/user/vehicles', ['Authorization' => 'Bearer ' . $tokenUser]);
        $responseListUser->assertStatus(200);
        $this->assertCount(2, $responseListUser->json('data'));

        $this->app['auth']->forgetGuards();

        // --- Show Vehicle ---
        $responseShowUser = $this->getJson('/api/user/vehicles/' . $vehicleLorryId, ['Authorization' => 'Bearer ' . $tokenUser]);
        $responseShowUser->assertStatus(200)
            ->assertJsonPath('data.vehicle_number', 'MH-12-AB-1234');

        $this->app['auth']->forgetGuards();

        // --- Update Vehicle (change to local and verify validation and update) ---
        $responseUpdateUser = $this->putJson('/api/user/vehicles/' . $vehicleLorryId, [
            'vehicle_type' => 'local',
            'driver_number' => '9999999999'
        ], ['Authorization' => 'Bearer ' . $tokenUser]);

        $responseUpdateUser->assertStatus(200)
            ->assertJsonPath('data.vehicle_type', 'local')
            ->assertJsonPath('data.driver_number', '9999999999');

        $this->assertDatabaseHas('vehicles', [
            'vehicle_id' => $vehicleLorryId,
            'vehicle_type' => 'local',
            'driver_number' => '9999999999',
            'vehicle_number' => null
        ]);

        $this->app['auth']->forgetGuards();

        // --- Update vehicle field without supplying vehicle_type (robustness check) ---
        $responseUpdatePartial = $this->putJson('/api/user/vehicles/' . $vehicleLocalId, [
            'status' => 0
        ], ['Authorization' => 'Bearer ' . $tokenUser]);

        $responseUpdatePartial->assertStatus(200)
            ->assertJsonPath('data.status', 0);

        $this->assertDatabaseHas('vehicles', [
            'vehicle_id' => $vehicleLocalId,
            'status' => 0
        ]);

        $this->app['auth']->forgetGuards();

        // --- Admin: List, Update, Delete ---
        $responseListAdmin = $this->getJson('/api/admin/vehicles', ['Authorization' => 'Bearer ' . $tokenAdmin]);
        $responseListAdmin->assertStatus(200);
        $this->assertCount(2, $responseListAdmin->json('data'));

        $this->app['auth']->forgetGuards();

        $responseDeleteAdmin = $this->deleteJson('/api/admin/vehicles/' . $vehicleLorryId, [], ['Authorization' => 'Bearer ' . $tokenAdmin]);
        $responseDeleteAdmin->assertStatus(200);

        $this->assertDatabaseMissing('vehicles', ['vehicle_id' => $vehicleLorryId]);
    }
}
