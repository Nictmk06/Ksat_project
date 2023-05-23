<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateConnected1Table extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('connected1', function(Blueprint $table)
		{
			$table->string('applicationid', 20)->nullable();
			$table->string('conapplid', 20)->nullable();
			$table->integer('conapplfrsrno')->nullable();
			$table->integer('conappltosrno')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('connected1');
	}

}
