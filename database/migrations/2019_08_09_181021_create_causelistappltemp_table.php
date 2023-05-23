<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCauselistappltempTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('causelistappltemp', function(Blueprint $table)
		{
			$table->string('applicationid', 20)->nullable();
			$table->integer('purposecode')->nullable();
			$table->integer('causelistsrno')->nullable();
			$table->text('iaflag')->nullable();
			$table->dateTime('createdon')->nullable();
			$table->text('createdby')->nullable();
			$table->integer('causelistcode', true);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('causelistappltemp');
	}

}
