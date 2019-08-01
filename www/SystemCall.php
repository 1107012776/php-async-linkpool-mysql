<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2019/5/23
 * Time: 6:51 AM
 */
require_once 'Task.php';
require_once 'Scheduler.php';
class SystemCall {
    protected $callback;

    public function __construct(callable $callback) {
        $this->callback = $callback;
    }

    public function __invoke(Task $task, Scheduler $scheduler) {
        $callback = $this->callback;
        return $callback($task, $scheduler);
    }
}