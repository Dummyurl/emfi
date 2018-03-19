<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToBlogPostTagsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('blog_post_tags', function(Blueprint $table)
		{
			$table->foreign('post_id')->references('id')->on('blog_posts')->onUpdate('RESTRICT')->onDelete('CASCADE');
			$table->foreign('tag_id')->references('id')->on('blog_tags')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('blog_post_tags', function(Blueprint $table)
		{
			$table->dropForeign('blog_post_tags_post_id_foreign');
			$table->dropForeign('blog_post_tags_tag_id_foreign');
		});
	}

}
