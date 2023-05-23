<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToDailyhearingTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('dailyhearing', function(Blueprint $table)
		{
			$table->foreign('"benchcode"', 'benchcode_fk')->references('"benchcode"')->on('bench')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('"applicationid"', 'applicationid_fk')->references('"applicationid"')->on('application')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('"purposecode"', 'purposecode_fk')->references('"purposecode"')->on('listPurpose')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('dailyhearing', function(Blueprint $table)
		{
			$table->dropForeign('benchcode_fk');
			$table->dropForeign('applicationid_fk');
			$table->dropForeign('purposecode_fk');
		});
	}

}
