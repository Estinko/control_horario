<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('dni');
            $table->string('birthdate')->nullable();
            $table->integer('vacation_left')->default(30);
            $table->integer('salary');
            $table->string('employee_key')->nullable();
            $table->decimal('bonus_job');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
