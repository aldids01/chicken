<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('areas', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->longText('description')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('bird_receiveds', function (Blueprint $table) {
            $table->id();
            $table->timestamp('time_of_arrival')->nullable();
            $table->string('batch_number')->nullable();
            $table->foreignId('supervisor_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('vehicle_no')->nullable();
            $table->integer('number_of_birds_delivered')->nullable();
            $table->integer('number_of_birds_dead_on_arrival')->nullable();
            $table->foreignId('recovery_officer_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->longText('remark')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('package_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->longText('description')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('blast_freezers', function (Blueprint $table) {
            $table->id();
            $table->string('batch_number')->nullable();
            $table->timestamp('time_in')->nullable();
            $table->longText('product_description')->nullable();
            $table->string('quality')->nullable();
            $table->foreignId('package_id')->nullable()->constrained('package_types')->onDelete('cascade');
            $table->integer('initial_temp')->nullable();
            $table->integer('freezer_temp')->nullable();
            $table->foreignId('handle_by_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->longText('remark')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('cold_rooms', function (Blueprint $table) {
            $table->id();
            $table->string('batch_number')->nullable();
            $table->timestamp('time_in')->nullable();
            $table->longText('product_description')->nullable();
            $table->string('quality')->nullable();
            $table->foreignId('package_id')->nullable()->constrained('package_types')->onDelete('cascade');
            $table->integer('blast_freezer')->nullable(); // This column name 'blast_freezer' seems to imply a relationship or a reading, but it's an int. If it refers to a foreign key, it should be changed.
            $table->integer('cold_room_temp')->nullable();
            $table->foreignId('transferred_by_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->longText('remark')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('cooling_vans', function (Blueprint $table) {
            $table->id();
            $table->string('driver_name');
            $table->foreignId('supervisor_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->decimal('total', 20, 2)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('hygiene_cleans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supervisor_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('hygiene_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hygiene_id')->nullable()->constrained('hygiene_cleans')->onDelete('cascade');
            $table->foreignId('area_id')->nullable()->constrained('areas')->onDelete('cascade');
            $table->enum('status', ['Clean', 'Dirty'])->nullable();
            $table->longText('remark')->nullable();
            $table->timestamps();
        });

        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->longText('description')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('material_inventory_checks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supervisor_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->decimal('total', 20, 2)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('line_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_id')->nullable()->constrained('material_inventory_checks')->onDelete('cascade');
            $table->foreignId('item_id')->nullable()->constrained('items')->onDelete('cascade');
            $table->integer('available_quantity')->nullable();
            $table->longText('remark')->nullable();
            $table->timestamps();
        });

        Schema::create('processings', function (Blueprint $table) {
            $table->id();
            $table->string('batch_number')->nullable();
            $table->timestamp('time_in')->nullable();
            $table->timestamp('time_out')->nullable();
            $table->integer('birds_slaughtered')->nullable();
            $table->integer('number_of_birds_processed')->nullable();
            $table->timestamp('chilling_time')->nullable();
            $table->enum('portioning_done', ['Yes', 'No'])->default('No');
            $table->string('whole_birds_package')->nullable();
            $table->integer('wings')->nullable();
            $table->integer('thighs')->nullable();
            $table->integer('drumsticks')->nullable();
            $table->integer('breast')->nullable();
            $table->integer('gizzard')->nullable();
            $table->integer('livers')->nullable();
            $table->integer('necks')->nullable();
            $table->integer('head_feet')->nullable();
            $table->integer('heart')->nullable();
            $table->integer('intestine_fat')->nullable();
            $table->integer('total_package')->nullable();
            $table->foreignId('package_id')->nullable()->constrained('package_types')->onDelete('cascade');
            $table->foreignId('package_officer_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('package_supervisor_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->longText('remark')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('utility_facilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supervisor_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('utility_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->longText('description')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('utility_line_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->nullable()->constrained('utility_items')->onDelete('cascade');
            $table->foreignId('utility_id')->nullable()->constrained('utility_facilities')->onDelete('cascade');
            $table->enum('status', ['Okay', 'Needs Attention'])->nullable();
            $table->longText('remark')->nullable();
            $table->timestamps();
        });

        Schema::create('van_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cooling_id')->nullable()->constrained('cooling_vans')->onDelete('cascade');
            $table->timestamp('departure_time')->nullable();
            $table->decimal('amount_products_carried', 20, 2)->nullable();
            $table->string('delivery_location')->nullable();
            $table->timestamp('return_time')->nullable();
            $table->string('fuel_level')->nullable();
            $table->longText('remark')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('van_items');
        Schema::dropIfExists('utility_line_items');
        Schema::dropIfExists('utility_items');
        Schema::dropIfExists('utility_facilities');
        Schema::dropIfExists('processings');
        Schema::dropIfExists('line_items');
        Schema::dropIfExists('material_inventory_checks');
        Schema::dropIfExists('items');
        Schema::dropIfExists('hygiene_items');
        Schema::dropIfExists('hygiene_cleans');
        Schema::dropIfExists('cooling_vans');
        Schema::dropIfExists('cold_rooms');
        Schema::dropIfExists('blast_freezers');
        Schema::dropIfExists('package_types');
        Schema::dropIfExists('bird_receiveds');
        Schema::dropIfExists('areas');
    }
};
