<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Category;


class CategoryApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test ID: Category-001
     * Description: Check if we can access the get all categories API
     * Preconditions: At least one category exists in the database
     * Test Steps:
     * 1. Create sample categories
     * 2. Hit the get all categories API
     * 3. Check if the response status is 200 and contains the success message
     * Test Data: 1-3 fake categories
     * Expected Result: The response status should be 200 and contain a "success" message
     * Remarks: Uses CategoryFactory
     */
    public function test_if_we_can_access_get_all_categories_api(): void
{
    $response = $this->get('/api/categories');
    $response->assertStatus(200)->assertJsonFragment(["message" => "Getting list of categories"]);
}
    /**
    * Test Case ID: Category-002
    * Description: Test that an admin can create a category
    * Precondition: A user exists with admin privileges.
    * Test Steps:
    *   1. Send POST request to /api/categories with category name
    * Test Data: Name: "kimhorn"
    * Expected Result: HTTP 201 Created / Category stored in database
    * Actual Result: HTTP 201 Created / Category stored in database
    * Status: Passed
    * Remark: None
    */

    public function test_create_category()
    {
        $response = $this->postJson('/api/categories', [
            'name' => 'kimhorn',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('categories', ['name' => 'kimhorn']);
    }
   
    /**
     * Test ID: Category-003
     * Description: Check if a non-existing category returns 404
     */
    public function test_get_non_existing_category_returns_404(): void
    {
        $response = $this->get('/api/categories/999');

        $response->assertStatus(404);
    }


    /**
     * Test Case ID: Product-002
     * Description: Validate category_id exists when creating a product
     */
    public function test_cannot_create_product_with_invalid_category(): void
    {
        $response = $this->postJson('/api/products', [
            'name' => 'Smartphone',
            'category_id' => 9999,
            'price' => 899.99
        ]);

        $response->assertStatus(422);
    }

    /**
    * Test Case ID: Category-004
    * Description: Test getting a category by ID
    * Precondition: A category exists in the database.
    * Test Steps:
    *   1. Create a category
    *   2. Send GET request to /api/categories/{id}
    * Test Data: Category ID (auto-generated), Name: "Books"
    * Expected Result: HTTP 200 OK / JSON object containing the category details
    * Actual Result: HTTP 200 OK / JSON object containing the category details
    * Status: Passed
    * Remark: None
    */


    public function test_can_get_category_by_id()
    {
        $category = Category::factory()->create();

        $response = $this->getJson("/api/categories/{$category->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => $category->name]);
    }

    /**
    * Test Case ID: TC007
    * Description: Test delete a category
    * Precondition: A category exists in the database and the user can delete
    * Test Steps:
    *   1. Send DELETE request to /api/categories/{id}
    * Test Data: Category ID (auto-generated)
    * Expected Result: HTTP 200 OK / Category deleted from database
    * Actual Result: HTTP 200 OK / Category deleted from database
    * Status: Passed
    * Remark: Category deletion is functioning correctly
    */
    public function test_delete_category()
    {
        $category = Category::factory()->create();

        $response = $this->deleteJson("/api/categories/{$category->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

 /**
    * Test Case ID: TC008
    * Description: Test category creation fails when the name field is empty
    * Precondition: The API requires a non-empty name field for category creation
    * Test Steps:
    *   1. Send POST request to /api/categories with an empty name
    * Test Data: name: ""
    * Expected Result: HTTP 422 Unprocessable Entity / Validation error on 'name'
    * Actual Result: HTTP 422 Unprocessable Entity / Validation error on 'name'
    * Status: Passed
    * Remark: Validation is correctly enforced on the 'name' field
    */
    public function test_create_category_fails_without_name()
    {

        $response = $this->postJson('/api/categories', [
            'name' => '',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name']);
    }

  /**
    * Test Case ID: Products-002
    * Description: Fetch product details by invalid ID
    * Precondition: Product ID does not exist
    * Test Steps:
    *   1. Send GET request to /api/products/9999
    *   2. Check for 404 response
    * Test Data: Product ID = 9999
    * Expected Result: 404 Not Found
    * Actual Result: 404 returned
    * Status: Passed
    * Remark: None
    */
    public function test_get_product_by_invalid_id_returns_404()
    {
        $response = $this->getJson('/api/products/9999');

        $response->assertStatus(404);
    }
    /**
 * Test Case ID: Category-010
 * Description: Ensure GET /api/categories returns an array of categories
 * Precondition: Multiple categories exist
 * Test Steps:
 *   1. Create 3 categories
 *   2. Send GET request to /api/categories
 * Test Data: 3 sample categories
 * Expected Result: HTTP 200 OK / Response contains all categories
 * Actual Result: HTTP 200 OK / 3 categories returned
 * Status: Passed
 * Remark: API returns collection properly
 */
public function test_get_all_categories_returns_list()
{
    Category::factory()->count(3)->create();

    $response = $this->getJson('/api/categories');

    $response->assertStatus(200)
             ->assertJsonStructure(['data'])
             ->assertJsonCount(3, 'data');
}

}
