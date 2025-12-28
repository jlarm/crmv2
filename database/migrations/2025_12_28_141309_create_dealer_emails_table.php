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
        Schema::create('dealer_emails', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('dealership_id')->constrained()->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('dealer_email_template_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('restrict');
            $table->boolean('customize_email')->default(false);
            $table->boolean('customize_attachment')->default(false);
            $table->json('recipients');
            $table->string('attachment')->nullable();
            $table->string('subject')->nullable();
            $table->text('message')->nullable();
            $table->date('start_date')->nullable();
            $table->date('last_sent')->nullable()->index();
            $table->date('next_send_date')->nullable()->index();
            $table->integer('frequency')->nullable();
            $table->boolean('paused')->default(false);
            $table->timestamps();

            $table->index(['paused', 'next_send_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dealer_emails');
    }
};
