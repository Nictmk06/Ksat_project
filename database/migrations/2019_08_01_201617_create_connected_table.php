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
			$table->string('applicationid', 20);
			$table->string('reason', 200)->nullable();
			$table->date('hearingdate')->nullable();
			$table->smallInteger('benchcode')->nullable();
			$table->date('orderdate')->nullable();
			$table->string('type')->nullable();
			$table->string('orderno', 50)->nullable();
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
		Schema::drop('connected');
	}

}
