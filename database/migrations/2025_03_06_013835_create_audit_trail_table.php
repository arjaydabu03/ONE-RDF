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
        Schema::create("audit_trail", function (Blueprint $table) {
            $table->increments("id");
            $table->string("user_id");
            $table->string("action");
            $table->string("module");
            $table->string("details");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("audit_trail");
    }
};
