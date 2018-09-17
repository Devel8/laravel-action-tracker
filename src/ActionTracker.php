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
        return $this->hasOne(Config::get('app.providers.users.model'));
    }

}
