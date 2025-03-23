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
            $table->string('uni_code', 50)->unique()->after('id');
            $table->string('avatar')->nullable()->after('email');
            $table->string('phone')->unique()->after('avatar');
            $table->enum('gender', ['male', 'female'])->after('phone');
            $table->date('birthday')->after('gender');
            $table->string('address')->nullable()->after('birthday');
            $table->timestamp('phone_verified_at')->nullable()->after('address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'uni_code',
                'avatar',
                'phone',
                'gender',
                'birthday',
                'address',
                'phone_verified_at'
            ]);
        });
    }
};
