<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateIADocumentTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('iadocument', function(Blueprint $table)
		{
			$table->string('applicationid', 20)->nullable();
			$table->date('iafillingdate')->nullable();
			$table->text('iaprayer')->nullable();
			$table->smallInteger('ianaturecode')->nullable();
			$table->date('iaregistrationdate')->nullable();
			$table->date('noticedate')->nullable();
			$table->date('hearingdate')->nullable();
			$table->date('orderdate')->nullable();
			$table->date('disposeddate')->nullable();
			$table->text('remark')->nullable();
			$table->text('iastatus')->nullable();
			$table->integer('benchcode')->nullable();
			$table->integer('purposecode')->nullable();
			$table->integer('courthallno')->nullable();
			$table->dateTime('createdon')->nullable();
			$table->string('createdby', 15)->nullable();
			$table->smallInteger('iasrno')->nullable();
			$table->smallInteger('documenttypecode')->nullable();
			$table->string('iano', 25)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('iadocument');
	}

}
