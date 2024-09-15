<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\FetchGoogleCalendarEvents;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

app()->resolving(Schedule::class, function (Schedule $schedule) {
    $schedule->command('fetch:calendar-events')->dailyAt('23:59'); //schedule to run at night
});