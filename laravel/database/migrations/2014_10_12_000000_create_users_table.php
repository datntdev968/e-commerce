<?php

use App\Models\Role;
use App\Models\UserGroup;
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
            $table->foreignIdFor(UserGroup::class)->constrained()->cascadeOnDelete();
            
            $table->string('uni_code', 50)->unique();
            $table->string('name');
            $table->string('avatar')->nullable();
            $table->string('email')->unique();
            $table->string('password');

            $table->string('phone')->unique();
            $table->enum('gender', ['male', 'female']);
            $table->date('birthday');
            $table->string('address')->nullable();

            $table->string('google_id')->nullable();

            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('phone_verified_at')->nullable();

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
