<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateConnectedTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('connected', function(Blueprint $table)
		{
			$table->string('applicationid', 20)->nullable();
			$table->string('reason', 200)->nullable();
			$table->date('hearingdate')->nullable();
			$table->smallInteger('benchcode')->nullable();
			$table->date('orderdate')->nullable();
			$table->string('type', 5)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('connected');
	}

}
