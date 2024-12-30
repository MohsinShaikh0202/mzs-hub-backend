<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMzsHubUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mzs_hub_users', function (Blueprint $table) {
            $table->id(); // Primary key, auto-increment
            $table->string('username')->unique(); // Unique username
            $table->string('password'); // Password
            $table->string('email')->unique(); // Unique email
            $table->string('mobile_number', 15)->nullable(); // Mobile number, max length 15
            $table->boolean('is_active')->default(1); // Active status, default 1
            $table->timestamps(); // created_at and updated_at
            $table->softDeletes(); // deleted_at for soft deletes
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mzs_hub_users');
    }
}
