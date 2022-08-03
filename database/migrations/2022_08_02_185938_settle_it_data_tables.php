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
		Schema::create('settleit', function (Blueprint $table) {
			$table->uuid('id')->primary();
			$table->string('status');
			$table->string('case_number')->nullable();
			$table->longText('dispute_details')->nullable();
			$table->uuid('plaintiff')->nullable();
			$table->uuid('defendant')->nullable();
			$table->string('currency')->default("USD");
			$table->string('settlement_amount')->nullable();
			$table->timestamps();
		});

		Schema::create('settleit_parties', function (Blueprint $table) {
			$table->uuid('id')->primary();
			$table->uuid('settleit_id');
			$table->foreign('settleit_id')->references('id')->on('settleit');
			$table->string('role')->nullable();
			$table->string('full_name')->nullable();
			$table->string('address')->nullable();
			$table->string('mobile_number')->nullable();
			$table->string('email_address')->nullable();
			$table->boolean('id_verified')->default(false);
			$table->string('validated_period')->default('no_limit');
			$table->boolean('is_legal_representative')->default(false);
			$table->timestamps();
		});

		Schema::create('settleit_parties_offer_data', function (Blueprint $table) {
			$table->uuid('id')->primary();
			$table->uuid('settleit_parties_id');
			$table->foreign('settleit_parties_id')->references('id')->on('settleit_parties');
			$table->string('currency')->default("USD");
			$table->string('amount')->nullable();
			$table->timestamps();
		});

		Schema::create('settleit_action_log', function (Blueprint $table) {
			$table->uuid('id')->primary();
			$table->uuid('settleit_parties_id');
			$table->foreign('settleit_parties_id')->references('id')->on('settleit_parties');
			$table->string('key')->nullable();
			$table->longText('data')->nullable();
			$table->timestamps();
		});

		Schema::create('file_data', function (Blueprint $table) {
			$table->uuid('id')->primary();
			$table->uuid('settleit_parties_id');
			$table->foreign('settleit_parties_id')->references('id')->on('settleit_parties');
			$table->string('file_url', 500)->nullable();
			$table->timestamps();
		});

		Schema::create('legal_data', function (Blueprint $table) {
			$table->uuid('id')->primary();
			$table->uuid('settleit_parties_id');
			$table->foreign('settleit_parties_id')->references('id')->on('settleit_parties');
			$table->string('full_name')->nullable();
			$table->string('address')->nullable();
			$table->string('mobile_number')->nullable();
			$table->string('email_address')->nullable();
			$table->string('company_name')->nullable();
			$table->timestamps();
		});

		Schema::create('id_verified', function (Blueprint $table) {
			$table->uuid('id')->primary();
			$table->uuid('settleit_parties_id');
			$table->foreign('settleit_parties_id')->references('id')->on('settleit_parties');
			$table->string('id_verified_id')->nullable();
			$table->boolean('id_confirmed')->default(false);
			$table->json('data')->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('id_verified');
		Schema::dropIfExists('legal_data');
		Schema::dropIfExists('file_data');
		Schema::dropIfExists('settleit_action_log');
		Schema::dropIfExists('settleit_parties_offer_data');
		Schema::dropIfExists('settleit_parties');
		Schema::dropIfExists('settleit');
	}
};
