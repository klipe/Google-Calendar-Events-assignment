<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\GoogleController;

class FetchGoogleCalendarEvents extends Command
{
    protected $signature = 'fetch:calendar-events';
    protected $description = 'Fetch Google Calendar events and save them to the database';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $controller = new GoogleController();
        $eventsArray = $controller->getCalendarEvents(); // Call the method that fetches and saves events
        $this->info('Google Calendar events have been fetched and saved.');
    }
}