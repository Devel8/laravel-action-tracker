<?php

return [

    /*
     * Disable all action tracking.
     * Therefore if disabled the actions will not be persisted in the database.
     */
    'disable' => false,

    /*
     * Action Tracker Model Class.
     */
    'model' => \Devel8\LaravelActionTracker\ActionTracker::class,

    /*
     * Database table where actions are tracked.
     */
    'table_name' => 'action_trackers',

    /*
     * Prefix word used to database columns name.
     */
    'prefix' => 'action_tracker'

];