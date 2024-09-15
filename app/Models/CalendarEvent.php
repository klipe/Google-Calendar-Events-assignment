<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id', 'summary', 'description', 'start_time', 'end_time', 'location'
    ];

    // Specify the primary key if it's not the default `id`
    protected $primaryKey = 'event_id'; 

    // Indicate that the primary key is not auto-incrementing
    public $incrementing = false; 

    // Set the primary key type
    protected $keyType = 'string'; 

    public $timestamps = true;
}