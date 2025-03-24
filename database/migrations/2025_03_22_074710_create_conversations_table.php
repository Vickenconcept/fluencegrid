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
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->foreignId('user_id')->constrained('users');
            $table->integer('influencer_id');
            $table->integer('campaign_id');
            $table->string('status');
            $table->string('original_url')->nullable();
            $table->string('short_code')->nullable();
            $table->integer('clicks')->default(0);
            $table->json('ip_addresses')->nullable();
            $table->integer('amount')->default(0);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
