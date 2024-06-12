<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    use HasFactory;

    protected $table = 'config';

    protected $fillable = [        
        'last_serial_number',
        'serial_port',
        'label_printer',
        'report_printer',
        'image_directory'
    ];
}
