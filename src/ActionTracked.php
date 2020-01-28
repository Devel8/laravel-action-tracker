<?php

namespace Devel8\LaravelActionTracker;

use Illuminate\Queue\SerializesModels;

class ActionTracked
{

    use SerializesModels;

    public ActionTracker $actionTracker;

    /**
     * ActionTracked constructor.
     *
     * @param ActionTracker $actionTracker
     */
    public function __construct(ActionTracker $actionTracker)
    {
        $this->actionTracker = $actionTracker;
    }

}