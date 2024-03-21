<?php

namespace Tests\Unit;

use App\Models\Url;
use App\Repositories\UrlRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UrlRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function testSaveUrl()
    {
        $url = new Url([
            'long_url' => 'https://example.com',
            'identifier' => 'test-identifier',
        ]);

        $repository = new UrlRepository();
        $repository->save($url);

        $this->assertDatabaseHas('urls', [
            'long_url' => 'https://example.com',
            'identifier' => 'test-identifier',
        ]);
    }

    public function testFindByIdentifierAndFolder()
    {
        $identifier = 'X0X0X0';
        $folder = 'test-folder';

        // Create a test URL in the database beforehand
        $url = new Url([
            'long_url' => 'https://example.com',
            'identifier' => $identifier,
            'folder' => $folder,
        ]);
        $url->save();

        $repository = new UrlRepository();
        $foundUrl = $repository->findByIdentifierAndFolder($identifier, $folder);

        $this->assertNotNull($foundUrl);
        $this->assertEquals($url->id, $foundUrl->id); // Verify retrieved URL matches saved one
        $this->assertEquals($url->long_url, $foundUrl->long_url);
    }

    public function testFindByLongUrlAndFolder()
    {
        $longUrl = 'https://example.com';
        $folder = 'X0X0X0';

        $url = new Url([
            'long_url' => $longUrl,
            'identifier' => 'test-identifier',
            'folder' => $folder
        ]);
        $url->save();

        $repository = new UrlRepository();
        $foundUrl = $repository->findByLongUrlAndFolder($longUrl, $folder);

        $this->assertNotNull($foundUrl);
        $this->assertEquals($url->id, $foundUrl->id); // Verify retrieved URL matches saved one
        $this->assertEquals($url->identifier, $foundUrl->identifier);
    }

    public function testFindByIdentifierAndFolderNonexistent()
    {
        $nonExistentIdentifier = 'X0X0X0';
        $folder = 'test-folder';

        $repository = new UrlRepository();
        $foundUrl = $repository->findByIdentifierAndFolder($nonExistentIdentifier, $folder);

        $this->assertNull($foundUrl); // Verify no URL is found
    }
}
