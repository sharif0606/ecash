<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 50);
            $table->string('username', 50)->unique();
            $table->string('email', 100)->unique();
            $table->string('mobileNumber', 20)->unique();
            $table->string('timezone')->nullable();
            $table->string('password', 32);
            $table->boolean('status', 1)->default(1)->comment('0 => inactive, 1 => active, 2 => pending, 3 => freez, 4 => block' );
            $table->unsignedTinyInteger('roleId');
            $table->unsignedInteger('userCreatorId')->nullable();
            $table->unsignedBigInteger('companyId')->nullable();
            $table->foreign('roleId')->references('id')->on('roles')->onDelete('cascade');
            $table->index(['companyId']);
            $table->index(['name']);
            $table->index(['username']);
            $table->index(['email']);
            $table->index(['mobileNumber']);
            $table->timestamps();
        });

        DB::table('users')->insert([
            [
                'name' => 'Superadmin',
                'username' => 'superadmin',
                'email' => 'superadmin@gmail.com',
                'mobileNumber' => '123456789',
                'password' => sha1(md5('superadmin')),
                'status' => 1,
                'roleId' => 1,
                'userCreatorId' => 1,
                'companyId' => null,
                'created_at' => Carbon::now()
            ],
			[
                'name' => 'Admin',
                'username' => 'admin',
                'email' => 'admin@gmail.com',
                'mobileNumber' => '123456789',
                'password' => sha1(md5('admin')),
                'status' => 1,
                'roleId' => 1,
                'userCreatorId' => 1,
                'companyId' => 1,
                'created_at' => Carbon::now()
            ]
        ]);
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
}
