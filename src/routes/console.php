<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspireido', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');