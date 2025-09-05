<?php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Company;
use App\Models\ShortUrl;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShortUrlFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        // run migrations and seed roles/superadmin if needed
    }

    public function test_admin_cannot_create_short_url()
    {
        $company = Company::factory()->create();
        $admin = User::factory()->create(['role'=>'Admin','company_id'=>$company->id]);
        $this->actingAs($admin)
             ->post('/urls', ['original_url'=>'https://laravel.com'])
             ->assertStatus(403);
    }

    public function test_member_cannot_create_short_url()
    {
        $member = User::factory()->create(['role'=>'Member']);
        $this->actingAs($member)
             ->post('/urls', ['original_url'=>'https://laravel.com'])
             ->assertStatus(403);
    }

    public function test_superadmin_cannot_create_short_url()
    {
        $super = User::factory()->create(['role'=>'SuperAdmin']);
        $this->actingAs($super)
             ->post('/urls', ['original_url'=>'https://laravel.com'])
             ->assertStatus(403);
    }

    public function test_admin_sees_urls_not_in_their_company()
    {
        $a = Company::factory()->create();
        $b = Company::factory()->create();

        $admin = User::factory()->create(['role'=>'Admin', 'company_id'=>$a->id]);
        $urlA = ShortUrl::factory()->create(['company_id'=>$a->id]);
        $urlB = ShortUrl::factory()->create(['company_id'=>$b->id]);

        $this->actingAs($admin)->get('/urls')->assertSee($urlB->short_code)->assertDontSee($urlA->short_code);
    }

    public function test_member_sees_urls_not_created_by_themselves()
    {
        $member = User::factory()->create(['role'=>'Member']);
        $mine = ShortUrl::factory()->create(['user_id'=>$member->id]);
        $other = ShortUrl::factory()->create();

        $this->actingAs($member)->get('/urls')->assertSee($other->short_code)->assertDontSee($mine->short_code);
    }

    public function test_short_url_requires_auth_and_redirects()
    {
        $sales = User::factory()->create(['role'=>'Sales']);
        $short = ShortUrl::factory()->create(['user_id'=>$sales->id, 'original_url'=>'https://example.com']);

        $this->get('/s/'.$short->short_code)->assertRedirect('/login');
        $this->actingAs($sales)->get('/s/'.$short->short_code)->assertRedirect('https://example.com');
    }
}
