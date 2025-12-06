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
        Schema::create('tours', function (Blueprint $table) {
            $table->id();
            $table->string('name');                      
            $table->string('slug')->unique();           
            $table->text('description')->nullable();    
            $table->string('location')->nullable();     
            $table->decimal('price', 12, 2);            
            $table->integer('duration_days')->default(1); 
            $table->integer('capacity')->nullable();    
            $table->string('thumbnail')->nullable();    
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tours');
    }
};
