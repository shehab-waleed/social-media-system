<?php

use App\Enums\Roles;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('role')->default(Roles::USER->value);
            $table->string('first_name');
            $table->string('last_name');
            $table->string('photo')->nullable();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('country');
            $table->string('password');
            $table->dateTime('email_verified_at')->nullable()->default(null);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
