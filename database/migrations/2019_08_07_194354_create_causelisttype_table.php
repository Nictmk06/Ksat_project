<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCauselisttypeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('causelisttype', function(Blueprint $table)
		{
			$table->integer('causelisttypecode')->primary('causelisttype_pkey');
			$table->string('causelistdesc', 50)->nullable();
			$table->char('period', 1)->nullable();
			$table->text('display')->nullable();
			$table->dateTime('createdon')->nullable();
			$table->string('createdby', 15)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('causelisttype');
	}

}
