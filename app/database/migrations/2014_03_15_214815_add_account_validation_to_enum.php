<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAccountValidationToEnum extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('tokens', function(Blueprint $table)
		{
            DB::statement("ALTER TABLE tokens MODIFY COLUMN type ENUM('reset_password', 'validate_account')");
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('tokens', function(Blueprint $table)
		{
			//
		});
	}

}
