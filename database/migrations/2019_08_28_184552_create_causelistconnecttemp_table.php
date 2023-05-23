<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCauselistconnecttempTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('causelistconnecttemp', function(Blueprint $table)
		{
			$table->string('applicationid', 20)->nullable();
			$table->integer('purposecode')->nullable();
			$table->integer('causelistsrno')->nullable();
			$table->char('iaflag', 1)->nullable();
			$table->dateTime('createdon')->nullable();
			$table->text('createdby')->nullable();
			$table->integer('causelistcode')->nullable();
			$table->string('enteredfrom', 15)->nullable();
			$table->smallInteger('appltypecode')->nullable();
			$table->string('conapplid', 20)->nullable();
			$table->string('type')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('causelistconnecttemp');
	}

}
