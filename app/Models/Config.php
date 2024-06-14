<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Config extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'config';

    protected $fillable = [        
        'last_serial_number',
        'serial_port',
        'label_printer',
        'report_printer',
        'image_directory',
        'baud_rate',
        'data_bits',
        'stop_bits',
        'parity',
    ];
}
