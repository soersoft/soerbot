<?php

require __DIR__ . '/../vendor/autoload.php';

use SoerBot\Database\Settings\Capsule;
use Illuminate\Database\Schema\Blueprint;
use SoerBot\Database\Settings\CapsuleSetup;

// Configuration Capsule
CapsuleSetup::setup();

if (!Capsule::schema()->hasTable('ranks')) {
    Capsule::schema()->create('ranks', function (Blueprint $table) {
        $table->increments('id');
        $table->string('user');
        $table->bigInteger('rank')->default(0);
        $table->timestamps();
    });
}
