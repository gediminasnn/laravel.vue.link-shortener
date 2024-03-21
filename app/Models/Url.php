<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Url extends Model
{
    use HasFactory;

    protected $fillable = [
        'long_url',
        'identifier',
        'folder',
    ];

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function getFolder(): ?string
    {
        return $this->folder;
    }
}
