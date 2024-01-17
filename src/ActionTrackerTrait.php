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
     * Action events list
     */
    protected array $actionEvents = [];

    /**
     * Action list
     */
//    protected array $actions = [];

    /**
     * @return mixed
     */
    public function actionTracker()
    {
        return $this->morphMany(Config::get('action-tracker.model'), 'action_tracker');
    }

    /**
     * Registry the action information
     *
     * @param string $action
     * @param string|null $message
     * @param null $extra
     * @param bool $model_tracking
     * @return mixed
     * @throws NotAllowedActionException
     * @throws \ReflectionException
     */
    public function doActionTracker(string $action, string $message = null, $extra = null, bool $model_tracking = false)
    {
        if(Config::get('action-tracker.disable'))
            return false;

        if(!$this->validateAction($action))
            throw new NotAllowedActionException();

        $prefix = Config::get('action-tracker.prefix');

        $actionTracker = new ActionTracker([
            'action' => $action,
            'message' => $message,
            'extra' => $extra,
            'user_id' => Auth::check() ? Auth::user()->getAuthIdentifier() : null,
            $prefix.'_type' => $this->getMorphClass(),
            $prefix.'_id' => $this->getKey(),
        ]);
        if($model_tracking){
            //TODO: save old and new model values
        }
        $result = $this->actionTracker()->save($actionTracker);
        event(new ActionTracked($actionTracker));
        $this->dispatchActionEvent($action, $actionTracker);
        return $result;
    }

    /**
     * Dispatch an event dynamically from action event list
     *
     * @param $action
     * @param $actionTracker
     * @throws \ReflectionException
     */
    private function dispatchActionEvent($action, $actionTracker)
    {
        if($this->validateActionEvent($action)) {
            $classname = $this->actionEvents[$action];
            $class = new \ReflectionClass($classname);
            $instance = $class->newInstanceArgs(array($actionTracker));
            event($instance);
        }
    }

    /**
     * Check whether is allowed action
     *
     * @param $action
     * @return bool
     */
    private function validateAction($action)
    {
        return in_array($action, $this->actions);
    }

    /**
     * Check whether is allowed action event
     *
     * @param $action
     * @return bool
     */
    private function validateActionEvent($action)
    {
        return in_array($action, $this->actionEvents);
    }
}
