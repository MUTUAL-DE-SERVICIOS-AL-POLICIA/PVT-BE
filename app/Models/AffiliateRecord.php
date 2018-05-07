<?php

namespace Muserpol\Models;

use Illuminate\Database\Eloquent\Model;

class AffiliateRecord extends Model
{
    //
    protected $table = 'affiliate_records_pvt';
    public function affiliate()
    {
        return $this->belongsTo('Muserpol\Models\Affiliate');
    }
    public function users()
    {
    	return $this->belongToMany(User::class, 'role_user');
    }
}
