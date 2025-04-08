<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Log;
use App\Models\Task;
use App\Models\User;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    $tasks = Task::with('user')
        ->where('is_complete', false)
        ->whereDate('due_date', now()->toDateString())
        ->get();
    foreach ($tasks as $task) {
        Mail::send("emails.notify-email", [
            'name' => $task->user->name,
            'task' => $task->title
        ], function ($message) use ($task) {
            $message->to($task->user->email)
                ->subject('Task Due Today: ' . $task->title);
        });
    }
})->everyMinute()->description('Send daily notifications for tasks due today');
