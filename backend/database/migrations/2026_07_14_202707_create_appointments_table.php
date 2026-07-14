<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->date('date');
            $table->string('time_slot', 5);
            $table->string('status', 20)->default('active');
            $table->timestamps();
        });

        DB::statement(
            'CREATE UNIQUE INDEX idx_appointments_slot_active
            ON appointments (date, time_slot)
            WHERE status = \'active\''
        );

        DB::statement(
            'CREATE UNIQUE INDEX idx_appointments_email_active
            ON appointments (email)
            WHERE status = \'active\''
        );
    }

    public function down(): void
    {
        DB::statement('DROP INDEX IF EXISTS idx_appointments_slot_active');
        DB::statement('DROP INDEX IF EXISTS idx_appointments_email_active');
        Schema::dropIfExists('appointments');
    }
};
