<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schedule;
use App\Console\Commands\GrabMessages;
use App\Console\Commands\ParseMessages;
use App\Console\Commands\SendPosts;

// Artisan::command('inspire', function () {
//     $this->comment(Inspiring::quote());
// })->purpose('Display an inspiring quote')->hourly();

Schedule::command(GrabMessages::class)->everyMinute();
Schedule::command(ParseMessages::class)->everyMinute();
Schedule::command(SendPosts::class)->everyMinute();
