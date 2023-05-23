<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDailyhearingTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('dailyhearing', function(Blueprint $table)
		{
			$table->bigInteger('hearingcode', true);
			$table->string('applicationid', 20);
			$table->integer('benchcode')->nullable();
			$table->integer('courthallno')->nullable();
			$table->date('hearingdate')->nullable();
			$table->smallInteger('listno')->nullable();
			$table->integer('purposecode')->nullable();
			$table->integer('causelistsrno')->nullable();
			$table->text('iaflag')->nullable();
			$table->string('business', 1)->nullable();
			$table->time('starttime')->nullable();
			$table->time('endtime')->nullable();
			$table->text('courtdirection')->nullable();
			$table->text('caseremarks')->nullable();
			$table->string('orderyn')->nullable();
			$table->smallInteger('casestatus')->nullable();
			$table->smallInteger('postafter')->nullable();
			$table->string('postaftercategory')->nullable();
			$table->date('nextdate')->nullable();
			$table->smallInteger('nextbenchcode')->nullable();
			$table->smallInteger('nextcausetypecode')->nullable();
			$table->string('takenonboard')->nullable();
			$table->smallInteger('documenttypecode')->nullable();
			$table->text('oficenote')->nullable();
			$table->smallInteger('nextpurposecode')->nullable();
			$table->date('orderdate')->nullable();
			$table->date('disposeddate')->nullable();
			$table->string('iano', 25)->nullable();
			$table->smallInteger('hearingstatus')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('dailyhearing');
	}

}
