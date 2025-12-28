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
        Schema::create('email_tracking_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sent_email_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('event_type')->index();
            $table->string('message_id')->index();
            $table->string('recipient_email')->index();
            $table->string('url')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('ip_address')->nullable();
            $table->json('mailgun_data')->nullable();
            $table->timestamp('event_timestamp')->index();
            $table->timestamps();

            $table->index(['sent_email_id', 'event_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_tracking_events');
    }
};
