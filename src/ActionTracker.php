<?php

namespace Devel8\LaravelActionTracker;

use Illuminate\Database\Eloquent\Model;
use Config;

/**
 * Class ActionTracker.
 *
 * @package namespace App\Entities;
 */
class ActionTracker extends Model
{
    protected $fillable = ['action', 'message', 'extra', 'user_id', 'action_tracker_type', 'action_tracker_id', 'created_at', 'updated_at'];
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function actionTracker(){
        return $this->morphTo();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user(){
        $model = $this->newRelatedInstance(Config::get('auth.providers.users.model'));
        return $this->belongsTo(Config::get('auth.providers.users.model'), 'user_id', $model->getKeyName());
    }

}
