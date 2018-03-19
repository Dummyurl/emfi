<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCmsPagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cms_pages', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('title');
			$table->string('page_title');
			$table->string('meta_title');
			$table->text('meta_description', 65535);
			$table->text('short_description', 65535);
			$table->text('description', 65535);
			$table->string('slug')->nullable()->index('slug');
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('cms_pages');
	}

}
