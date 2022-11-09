<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('users', function (Blueprint $table) {
			$table->uuid('id')->primary();
			$table->string('full_name');
			$table->string('email')->unique();
			$table->string('mobile_number')->nullable();
			$table->string('country')->nullable();
			$table->boolean('id_verified')->default(false);
			$table->boolean('account_active')->default(true);
			$table->boolean('is_super_admin')->default(false);
			$table->timestamp('email_verified_at')->nullable();
			$table->string('password');
			$table->rememberToken();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('users');
	}
};
