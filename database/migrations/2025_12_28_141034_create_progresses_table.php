<?php

declare(strict_types=1);

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
        Schema::create('progresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('dealership_id')->constrained()->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('contact_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('progress_category_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('set null');
            $table->text('details');
            $table->date('date')->nullable()->index();
            $table->timestamps();

            $table->index(['dealership_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('progresses');
    }
};
