<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdminUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('admin_users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_type_id')->nullable()->index('admin_user_type_fk_1');
			$table->string('name');
			$table->string('email')->unique();
			$table->string('password');
			$table->boolean('status')->default(1);
			$table->timestamp('last_login_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->string('remember_token', 100)->nullable();
			$table->string('slug')->nullable();
			$table->timestamps();
			$table->string('phone')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('admin_users');
	}

}
