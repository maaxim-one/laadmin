<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('login')->after('name')->unique()->nullable();
            $table->unsignedBigInteger('role_id')->after('remember_token')->nullable()->default(1);
            $table->dateTime('last_visit')->after('remember_token')->nullable();
            $table->string('email')->nullable()->change();

            $table->foreign('role_id')
                ->references('role_id')
                ->on('roles');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
