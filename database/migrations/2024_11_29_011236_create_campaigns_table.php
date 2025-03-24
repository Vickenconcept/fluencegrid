<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function Laravel\Prompts\table;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title'); 
            $table->text('description');
            $table->text('task')->nullable(); 
            $table->integer('budget'); 
            $table->date('start_date')->nullable(); 
            $table->date('end_date')->nullable(); 
            $table->date('invite_end_date')->nullable(); 
            $table->integer('type')->default(1); 
            $table->string('status')->default('pending'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
