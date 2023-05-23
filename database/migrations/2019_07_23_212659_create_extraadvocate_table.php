<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateExtraadvocateTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('extraadvocate', function(Blueprint $table)
		{
			$table->string('applicationid', 20);
			$table->string('advocatetype', 1)->nullable();
			$table->smallInteger('partysrno');
			$table->string('enrollmentno');
			$table->string('display', 1)->nullable();
			$table->string('active', 1)->nullable();
			$table->text('remarks')->nullable();
			$table->integer('advocatecode', true);
			$table->date('enrolleddate')->nullable();
			$table->string('createdby', 15)->nullable();
			$table->date('createdon')->nullable();
			$table->string('updatedby', 15)->nullable();
			$table->date('updatedon')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('extraadvocate');
	}

}
