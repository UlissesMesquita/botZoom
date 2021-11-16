<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OauthZoom extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',      
        'access_token',
        'refresh_token',

    ];

    public $timestamps = false;
    protected $table = 'table_access_tokens';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $connection = 'sqlite';
}