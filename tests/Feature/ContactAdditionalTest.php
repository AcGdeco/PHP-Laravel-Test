<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Contact;

class ContactAdditionalTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function store_rejects_duplicate_contact_and_email()
    {
        $user = User::factory()->create();

        // existing contact
        Contact::create([
            'name' => 'Existing Contact',
            'contact' => '123456789',
            'email' => 'existing@example.com',
        ]);

        // attempt to create another with same contact and email
        $response = $this->actingAs($user)->post(route('contacts.store'), [
            'name' => 'Another Name',
            'contact' => '123456789',
            'email' => 'existing@example.com',
        ]);

        $response->assertSessionHasErrors(['contact', 'email']);
    }

    /** @test */
    public function protected_contact_routes_redirect_to_login_when_guest()
    {
        $contact = Contact::create([
            'name' => 'Contact Test',
            'contact' => '987654321',
            'email' => 'test@example.com',
        ]);

        // GET /contacts/create
        $this->get(route('contacts.create'))->assertRedirect(route('login'));

        // POST /contacts
        $this->post(route('contacts.store'), [])->assertRedirect(route('login'));

        // GET /contacts/{contact}
        $this->get(route('contacts.show', $contact->id))->assertRedirect(route('login'));

        // GET /contacts/{contact}/edit
        $this->get(route('contacts.edit', $contact->id))->assertRedirect(route('login'));

        // PUT /contacts/{contact}
        $this->put(route('contacts.update', $contact->id), [])->assertRedirect(route('login'));

        // DELETE /contacts/{contact}
        $this->delete(route('contacts.destroy', $contact->id))->assertRedirect(route('login'));
    }
}
