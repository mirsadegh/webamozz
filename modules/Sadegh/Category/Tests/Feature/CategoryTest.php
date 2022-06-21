<?php
namespace Sadegh\Category\Tests\Feature;

use Tests\TestCase;

use Sadegh\User\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;




class CategoryTest extends TestCase
{
       use WithFaker,RefreshDatabase;



       public function test_permitted_user_can_see_categories_panel()
       {
           $this->withoutExceptionHandling();

           $user = User::factory()->create();
           $this->actingAs($user);


        //    $this->actionAsAdmin();
           $this->get(route('categories.index'))->assertOk();
       }
       public function test_normal_user_can_not_see_categories_panel()
       {

           $this->actionAsUser();
           $this->get(route('categories.index'))->assertStatus(403);

       }

       public function test_permitted_user_can_create_category()
       {
           $this->withoutExceptionHandling();
           $this->actionAsAdmin();
           $this->createCategory();

           $this->assertEquals(1, Category::all()->count());

       }

       public function test_permitted_user_can_update_category()
       {
           $newTitle = "assdddff";
           $this->withoutExceptionHandling();
           $this->actionAsAdmin();
           $this->createCategory();
           $this->assertEquals(1, Category::all()->count());
           $this->patch(route('categories.update', 1), ['title' => $newTitle, "slug" => $this->faker->word]);

           $this->assertEquals(1, Category::whereTitle($newTitle)->count());
       }

       public function test_user_can_delete_category()
       {
           $this->actionAsAdmin();
           $this->createCategory();
           $this->assertEquals(1, Category::all()->count());

           $this->delete(route('categories.destroy', 1))->assertOk();
       }

       public function actionAsAdmin()
       {
           $this->actingAs(User::factory()->create());
           $this->seed(RolePermissionTableSeeder::class);
           auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_CATEGORIES);
       }

       public function actionAsUser()
       {
           $this->actingAs(User::factory()->create());
           $this->seed(RolePermissionTableSeeder::class);
       }

       private function createCategory()
       {
         return  $this->post(route('categories.store'), ['title' => $this->faker->word, "slug" => $this->faker->word]);
       }
}

