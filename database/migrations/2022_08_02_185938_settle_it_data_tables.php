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
			$table->string('dispute_title')->nullable();
			$table->longText('dispute_details')->nullable();
			$table->uuid('creator_id')->nullable();
			$table->string('creator_role')->nullable();
			$table->uuid('plaintiff')->nullable();
			$table->uuid('defendant')->nullable();
			$table->string('currency')->default("USD");
			$table->string('settlement_total_amount')->nullable();
			$table->string('settlement_amount')->nullable();
			$table->string('step')->default('1_1');
			$table->string('short_id')->index();
			$table->boolean('settleit_show_settlement_amount')->default(false);
			$table->index([
				'creator_id',
				'plaintiff',
				'defendant'
			]);
			$table->timestamps();
		});

		Schema::create('settleit_parties', function (Blueprint $table) {
			$table->uuid('id')->primary();
			$table->uuid('settleit_id');
			$table->foreign('settleit_id')->references('id')->on('settleit');
			$table->uuid('user_id')->nullable();
			$table->foreign('user_id')->references('id')->on('users');
			$table->string('role')->nullable();
			$table->string('full_name')->nullable();
			$table->string('address')->nullable();
			$table->string('mobile_number')->nullable();
			$table->string('email_address')->nullable();
			$table->boolean('id_verified')->default(false);
			$table->string('validated_period')->default('no_limit');
			$table->boolean('is_legal_representative')->default(false);
			$table->string('device')->nullable();
			$table->index([
				'settleit_id',
				'user_id'
			]);
			$table->timestamps();
		});

		Schema::create('settleit_parties_offer_data', function (Blueprint $table) {
			$table->uuid('id')->primary();
			$table->uuid('settleit_parties_id');
			$table->foreign('settleit_parties_id')->references('id')->on('settleit_parties');
			$table->string('currency')->default("USD");
			$table->string('total_amount')->nullable();
			$table->string('settleit_amount')->nullable();
			$table->boolean('settleit_show_settlement_amount')->default(false);
			$table->index(['settleit_parties_id']);
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
			$table->uuid('settleit_id');
			$table->foreign('settleit_id')->references('id')->on('settleit');
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
			$table->uuid('user_id')->nullable();
			$table->foreign('user_id')->references('id')->on('users');
			$table->string('id_verified_id')->nullable();
			$table->boolean('id_confirmed')->default(false);
			$table->json('data')->nullable();
			$table->index([
				'settleit_parties_id',
				'user_id'
			]);
			$table->timestamps();
		});

		//		Schema::create('session_log', function (Blueprint $table) {
		//			$table->uuid('id')->primary();
		//			$table->string('key')->nullable();
		//			$table->longText('data')->nullable();
		//			$table->timestamps();
		//		});
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
