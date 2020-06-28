<?php
namespace Muserpol\Models;

use Illuminate\Database\Eloquent\Model;
use Muserpol\Helpers\Util;
use Muserpol\User;

class Record extends Model
{
    public $timestamps = true;
    public $guarded = ['id'];
    protected $fillable = ['user_id', 'record_type_id', 'recordable_id', 'recordable_type', 'action'];

    /*public function getActionAttribute()
    {
        $action = "[{$this->record_type->display_name}] El usuario {$this->user->username} {$this->attributes['action']}. ";
        if ($this->recordable) {
            $action .= Util::translate($this->attributes['recordable_type']);
            $action .= ': ';
            switch (get_class($this->recordable)) {
                case 'App\Affiliate':
                    $action .= $this->recordable->full_name;
                    break;
                case 'App\User':
                    $action .= $this->recordable->username;
                    break;
            }
        }
        unset($this['record_type'], $this['user'], $this['recordable']);
        return $action;
    }*/

    public function recordable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function record_type()
    {
        return $this->belongsTo(RecordType::class);
    }
    //
    /*public $guarded =  [];

    public function recordable()
    {
        return $this->morphTo();
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function record_type()
    {
        return $this->belongsTo(RecordType::class);
    }*/
}
