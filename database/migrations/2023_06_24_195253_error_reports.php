<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('error_reports', function (Blueprint $table) {
            $table->id('report_id');
            $table->string('report_file');
            $table->integer('report_code');
            $table->integer('report_line');
            $table->longText('report_message');
            $table->json('report_events');

            $table->unsignedBigInteger('report_fixed_user')->nullable();
            $table->timestamp('report_read_at')->nullable();
            $table->timestamp('report_fixed_at')->nullable();
            $table->timestamp('report_created_at')->nullable();

            $table->foreign('report_fixed_user')
                ->references('id')
                ->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('error_reports');
    }
};
