<?php

namespace Muserpol\Models;

use Illuminate\Database\Eloquent\Model;

class AffiliateUser extends Model
{
    // use HasFactory;

    public $timestamps = true;
    protected $fillable = [
        'affiliate_token_id',
        'username',
        'password',
        'access_status',
        'password_update_code'
    ];
    protected $primaryKey = 'affiliate_token_id';
    public $incrementing = false;

    public function affiliate_token()
    {
        return $this->belongsTo(AffiliateToken::class);
    }
}
