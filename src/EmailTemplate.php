<?php

namespace StaffCollab\Email;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    protected $table = 'email_templates';

    protected $guarded = [];

    protected $casts = [
        'recipient_keys' => 'array',
        'attachment_keys' => 'array',
    ];

    public $greeting;

    public function company()
    {
        return $this->belongsTo(config('email.tenant_model'));
    }
}
