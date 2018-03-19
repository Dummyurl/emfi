<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBlogPostTagsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('blog_post_tags', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('post_id')->unsigned()->index('blog_post_tags_post_id_foreign');
			$table->integer('tag_id')->unsigned()->index('blog_post_tags_tag_id_foreign');
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
		Schema::drop('blog_post_tags');
	}

}
