<?php
// app/Models/Setting.php
namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Setting extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'settings';
    protected $fillable = ['key', 'value'];
}
