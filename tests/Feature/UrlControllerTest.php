<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Url;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UrlControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testRedirectByIdentifierAndFolderSuccess()
    {
        $identifier = 'testIdentifier';
        $folder = 'testFolder';

        $url = Url::factory()->create([
            'identifier' => $identifier,
            'folder' => $folder,
            'long_url' => 'https://www.example.com',
        ]);

        $response = $this->get(route('redirect-foldered', ['folder' => $folder, 'identifier' => $identifier]));

        $response->assertRedirect($url->long_url);
    }

    public function testRedirectByIdentifierAndFolderNotFound()
    {
        $response = $this->get('/nonexistentFolder/nonexistentIdentifier');

        $response->assertStatus(404);
    }

    public function testRedirectByIdentifierSuccess()
    {
        $identifier = 'testIdentifier';

        $url = Url::factory()->create([
            'identifier' => $identifier,
            'long_url' => 'https://www.example.com',
        ]);

        $response = $this->get("/{$identifier}");

        $response->assertRedirect($url->long_url);
    }

    public function testRedirectByIdentifierNotFound()
    {
        $response = $this->get('/nonexistentIdentifier');

        $response->assertStatus(404);
    }

    public function testShortenUrlSuccess()
    {
        $csrfToken = $this->getCsrfToken();

        $response = $this->postJson(route('shorten-url'), [
            'long_url' => 'https://www.example.com'
        ], ['X-CSRF-TOKEN' => $csrfToken]);

        $response->assertStatus(200)
            ->assertJsonStructure(['url']);

        $this->assertDatabaseHas('urls', [
            'long_url' => 'https://www.example.com',
        ]);
    }

    public function testShortenUrlValidationFailure()
    {
        $csrfToken = $this->getCsrfToken();

        $response = $this->postJson(route('shorten-url'), [
            'long_url' => 'not a valid url'
        ], ['X-CSRF-TOKEN' => $csrfToken]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['long_url']);
    }

    public function testShortenFolderedUrlSuccess()
    {
        $csrfToken = $this->getCsrfToken();

        $response = $this->postJson(route('shorten-foldered-url'), [
            'long_url' => 'https://www.example.com',
            'folder' => 'myFolder'
        ], ['X-CSRF-TOKEN' => $csrfToken]);

        $response->assertStatus(200)
            ->assertJsonStructure(['url']);

        $this->assertDatabaseHas('urls', [
            'long_url' => 'https://www.example.com',
            'folder' => 'myFolder'
        ]);
    }

    public function testShortenFolderedUrlValidationFailure()
    {
        $csrfToken = $this->getCsrfToken();

        $response = $this->postJson(route('shorten-url'), [
            'long_url' => 'https://www.example.com',
            'folder' => 'invalid_folder_format'
        ], ['X-CSRF-TOKEN' => $csrfToken]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['folder']);
    }

    private function getCsrfToken()
    {
        $response = $this->get('/token');
        return $response->getContent();
    }
}
