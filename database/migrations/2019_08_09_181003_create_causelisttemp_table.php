<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCauselisttempTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('causelisttemp', function(Blueprint $table)
		{
			$table->bigInteger('causelistcode')->nullable();
			$table->integer('causelisttypecode')->nullable();
			$table->integer('benchcode')->nullable();
			$table->integer('courthallno')->nullable();
			$table->date('causelistdate')->nullable();
			$table->date('causelistfromdate')->nullable();
			$table->date('causelisttodate')->nullable();
			$table->smallInteger('listno')->nullable();
			$table->integer('totalapplication')->nullable();
			$table->string('benchtypename', 100)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('causelisttemp');
	}

}
