<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Faq;
use App\Support\SiteCache;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Tests\TestCase;

class FaqManagementTest extends TestCase
{
    protected function getAdmin(): Admin
    {
        $admin = Admin::query()->first();
        if (! $admin) {
            $admin = Admin::create([
                'name' => 'Test Admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('Admin@12345678'),
                'role' => 'admin',
                'session_version' => 1,
            ]);
        }

        return $admin;
    }

    protected function asAdmin()
    {
        $admin = $this->getAdmin();

        return $this->actingAs($admin, 'admin')
            ->withSession([
                'admin_session_version' => (int) $admin->session_version,
                'admin_last_activity_at' => now()->timestamp,
            ])
            ->withoutMiddleware(ValidateCsrfToken::class);
    }

    public function test_faq_page_renders_successfully(): void
    {
        $response = $this->get(route('faq.index'));
        $response->assertStatus(200);
        $response->assertSee('Frequently Asked Questions');
        $response->assertSee('Everything You Need to Know');
    }

    public function test_faq_page_displays_active_faqs(): void
    {
        SiteCache::forgetPublicPages();

        $faq = Faq::query()->create([
            'question' => 'How do dividends work for King Lotus share owners?',
            'answer' => 'Dividends are distributed annually based on operational profits.',
            'is_active' => true,
        ]);

        $response = $this->get(route('faq.index'));
        $response->assertStatus(200);
        $response->assertSee('How do dividends work for King Lotus share owners?');
        $response->assertSee('Dividends are distributed annually based on operational profits.');

        $faq->delete();
        SiteCache::forgetPublicPages();
    }

    public function test_faq_page_hides_inactive_faqs(): void
    {
        SiteCache::forgetPublicPages();

        $faq = Faq::query()->create([
            'question' => 'Secret Inactive Question That Should Not Show Up',
            'answer' => 'Secret answer for internal draft only.',
            'is_active' => false,
        ]);

        $response = $this->get(route('faq.index'));
        $response->assertStatus(200);
        $response->assertDontSee('Secret Inactive Question That Should Not Show Up');

        $faq->delete();
    }

    public function test_public_faq_items_endpoint_returns_json_results(): void
    {
        $response = $this->getJson(route('faq.items', ['page' => 1]));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => ['id', 'question', 'answer'],
            ],
            'current_page',
            'has_more',
            'total',
            'per_page',
        ]);
    }

    public function test_unauthenticated_users_cannot_manage_faqs(): void
    {
        $faq = Faq::query()->create([
            'question' => 'Test Auth Protection',
            'answer' => 'Test Answer',
            'is_active' => true,
        ]);

        $responseStore = $this->withoutMiddleware(ValidateCsrfToken::class)
            ->post(route('admin.content.faqs.store'), [
                'question' => 'Hacked Question',
                'answer' => 'Hacked Answer',
            ]);
        $responseStore->assertRedirect(route('admin.login'));

        $responseUpdate = $this->withoutMiddleware(ValidateCsrfToken::class)
            ->patch(route('admin.content.faqs.update', $faq), [
                'question' => 'Updated By Guest',
                'answer' => 'Updated Answer',
            ]);
        $responseUpdate->assertRedirect(route('admin.login'));

        $responseDestroy = $this->withoutMiddleware(ValidateCsrfToken::class)
            ->delete(route('admin.content.faqs.destroy', $faq));
        $responseDestroy->assertRedirect(route('admin.login'));

        $faq->delete();
    }

    public function test_admin_can_view_faqs_in_content_management(): void
    {
        $response = $this->asAdmin()->get(route('admin.content.index', ['module' => 'faq']));
        $response->assertStatus(200);
        $response->assertSee('FAQ Management');
        $response->assertSee('Frequently Asked Questions');
        $response->assertSee('Add FAQ');
        $response->assertDontSee('Sort Order');
    }

    public function test_admin_can_paginate_faqs_items_endpoint(): void
    {
        $response = $this->asAdmin()->getJson(route('admin.content.faqs.items', ['page' => 1]));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => ['id', 'question', 'answer', 'is_active', 'updated_at_human', 'update_url', 'destroy_url'],
            ],
            'current_page',
            'has_more',
            'total',
            'per_page',
        ]);
    }

    public function test_newest_faq_appears_first(): void
    {
        SiteCache::forgetPublicPages();

        $faqOld = Faq::query()->create([
            'question' => 'Older Question In DB',
            'answer' => 'Old answer',
            'is_active' => true,
        ]);

        $faqNew = Faq::query()->create([
            'question' => 'Brand New Latest Question In DB',
            'answer' => 'Newest answer',
            'is_active' => true,
        ]);

        $ordered = Faq::query()->ordered()->get();
        $this->assertEquals($faqNew->id, $ordered->first()->id);

        $faqOld->delete();
        $faqNew->delete();
        SiteCache::forgetPublicPages();
    }

    public function test_admin_can_create_faq(): void
    {
        $response = $this->asAdmin()->post(route('admin.content.faqs.store'), [
            'question' => 'What is the share transfer process?',
            'answer' => 'Shares can be transferred via the official legal deed.',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.content.index', ['module' => 'faq']));
        $response->assertSessionHas('success');

        $created = Faq::query()->where('question', 'What is the share transfer process?')->first();
        $this->assertNotNull($created);
        $this->assertEquals('Shares can be transferred via the official legal deed.', $created->answer);
        $this->assertTrue((bool) $created->is_active);

        $created->delete();
    }

    public function test_admin_can_update_faq(): void
    {
        $faq = Faq::query()->create([
            'question' => 'Original Question Before Update',
            'answer' => 'Original Answer',
            'is_active' => true,
        ]);

        $response = $this->asAdmin()->patch(route('admin.content.faqs.update', $faq), [
            'question' => 'Updated Question By Admin',
            'answer' => 'Updated Answer By Admin with more clarity.',
            'is_active' => '0',
        ]);

        $response->assertRedirect(route('admin.content.index', ['module' => 'faq']));
        $response->assertSessionHas('success');

        $faq->refresh();
        $this->assertEquals('Updated Question By Admin', $faq->question);
        $this->assertEquals('Updated Answer By Admin with more clarity.', $faq->answer);
        $this->assertFalse((bool) $faq->is_active);

        $faq->delete();
    }

    public function test_admin_can_delete_faq(): void
    {
        $faq = Faq::query()->create([
            'question' => 'Question To Be Deleted',
            'answer' => 'Answer To Be Deleted',
            'is_active' => true,
        ]);

        $response = $this->asAdmin()->delete(route('admin.content.faqs.destroy', $faq));
        $response->assertRedirect(route('admin.content.index', ['module' => 'faq']));
        $response->assertSessionHas('success');

        $this->assertNull(Faq::query()->find($faq->id));
    }
}
