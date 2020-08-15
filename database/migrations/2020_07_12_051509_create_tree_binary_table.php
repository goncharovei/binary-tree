<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTreeBinaryTable extends Migration {
	
	protected $table_name = 'tree_binary';
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create($this->table_name, function (Blueprint $table) {
			$table->engine = 'MyISAM';
			$table->increments('id');
			$table->unsignedInteger('parent_id')->nullable();
			$table->boolean('position')->nullable();
			$table->string('path', 12288)->collation('latin1_general_ci');
			$table->unsignedInteger('level');
		
		});
		
		DB::statement("ALTER TABLE `tree_binary` ADD UNIQUE (`path`(500)) USING BTREE;");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists($this->table_name);
	}

}
