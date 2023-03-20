<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('prefixname', 255)->default(NULL)->nullable();
            $table->string('firstname', 255)->default(NULL);
            $table->string('middlename', 255)->default(NULL)->nullable();
            $table->string('lastname', 255)->default(NULL);
            $table->string('suffixname', 255)->default(NULL)->nullable();
            $table->string('username', 255)->unique()->default(NULL);
            $table->string('email', 255)->unique();
            $table->text('password')->default(NULL);
            $table->text('photo')->default(NULL)->nullable();
            $table->string('type', 255)->default(User::TYPE_USER)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
