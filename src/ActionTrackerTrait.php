<?php


namespace Devel8\LaravelActionTracker;

use Config;
use Auth;

/**
 * Trait ActionTrackerTrait
 * @package Devel8\LaravelActionTracker
 */
trait ActionTrackerTrait
{

    /**
     * @return mixed
     */
    public function actionTracker(){
        return $this->morphMany(Config::get('action-tracker.model'), 'action_tracker');
    }

    /**
     * Registry the action information
     *
     * @param $action
     * @param $message
     * @param $extra
     * @param bool $model_tracking
     * @return mixed
     * @throws NotAllowedActionException
     */
    public function doActionTracker($action, $message = null, $extra = null, $model_tracking = false){

        if(!$this->validateAction($action))
            throw new NotAllowedActionException();

        $prefix = Config::get('action-tracker.prefix');

        $actionTracker = [
            'action' => $action,
            'message' => $message,
            'extra' => $extra,
            'user_id' => Auth::check() ? Auth::user()->getAuthIdentifier() : null,
            $prefix.'_type' => $this->getMorphClass(),
            $prefix.'_id' => $this->getKey(),
            'created_at' => new \DateTime(),
            'updated_at' => new \DateTime(),
        ];

        if($model_tracking){
            //TODO: save old and new model values
        }

        return $this->actionTracker()->insert($actionTracker);
    }

    /**
     * Check whether is allowed action
     *
     * @param $action
     * @return bool
     */
    private function validateAction($action){
        return in_array($action, $this->actions);
    }
}