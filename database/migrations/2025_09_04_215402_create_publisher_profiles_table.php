<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('publisher_profiles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('publisher_id')->constrained('publishers')->cascadeOnDelete();
            $table->string('first_name');
            $table->string('last_name');
            $table->integer('age');
            $table->string('gender');
            $table->string('governorate');
            $table->string('city');
            $table->string('address');
            $table->text('bio');
            $table->string('verified')->default('notverified'); // for media
            $table->timestamp('bith_date');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publisher_profiles');
    }
};
