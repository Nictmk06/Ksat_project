<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToIADocumentTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('iadocument', function(Blueprint $table)
		{
			$table->foreign('"purposecode"', 'purposecode')->references('"purposecode"')->on('feepurpose')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('"benchcode"', 'benchcode')->references('"benchcode"')->on('bench')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('"applicationid"', 'applicationid')->references('"applicationid"')->on('application')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('iadocument', function(Blueprint $table)
		{
			$table->dropForeign('purposecode');
			$table->dropForeign('benchcode');
			$table->dropForeign('applicationid');
		});
	}

}
