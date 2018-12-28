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

if (!Capsule::schema()->hasTable('users')) {
    Capsule::schema()->create('users', function (Blueprint $table) {
        $table->string('id')->unique();
        $table->string('name')->nullable();
        $table->bigInteger('rank')->default(0);
        $table->timestamps();

        $table->primary('id');
    });
}

if (!Capsule::schema()->hasTable('awards')) {
    Capsule::schema()->create('awards', function (Blueprint $table) {
        $table->string('id')->unique();
        $table->string('type');
        $table->string('description')->nullable();
        $table->float('rate')->default(1);
        $table->bigInteger('rank')->default(0);
        $table->timestamps();
    });
}

if (!Capsule::schema()->hasTable('award_user')) {
    Capsule::schema()->create('award_user', function (Blueprint $table) {
        $table->string('type_id');
        $table->string('user_from');
        $table->string('user_to');
        $table->string('comment')->nullable();
        $table->timestamps();

        $table->primary(['type_id', 'user_from', 'user_to']);
        $table->foreign('user_from')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('user_to')->references('id')->on('users')->onDelete('cascade');
    });
}
