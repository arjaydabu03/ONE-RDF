<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("systems", function (Blueprint $table) {
            $table->increments("id");
            $table->string("url_holder");
            $table->longText("token");
            $table->string("system_image");
            $table->string("system_name");
            $table->string("category_id");
            $table->string("slice")->nullable();
            $table->string("last_update_by")->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("systems");
    }
};
