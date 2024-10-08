<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schedule;
use App\Domain\Orders\Commands\GrabMessages;
use App\Domain\Orders\Commands\ParseMessages;
use App\Domain\Orders\Commands\SendPosts;

// Artisan::command('inspire', function () {
//     $this->comment(Inspiring::quote());
// })->purpose('Display an inspiring quote')->hourly();

Schedule::command(GrabMessages::class)->everyMinute();
Schedule::command(ParseMessages::class)->everyMinute();
Schedule::command(SendPosts::class)->everyMinute();
