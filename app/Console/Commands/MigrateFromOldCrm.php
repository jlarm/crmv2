<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

final class MigrateFromOldCrm extends Command
{
    protected $signature = 'crm:migrate-data {--fresh : Truncate all tables before migrating}';

    protected $description = 'Migrate data from old CRM database to new CRMv2 database';

    public function handle(): int
    {
        if ($this->option('fresh')) {
            if (! $this->confirm('This will delete all existing data in crmv2. Are you sure?')) {
                $this->info('Migration cancelled.');

                return Command::SUCCESS;
            }

            $this->truncateTables();
        }

        $this->info('Starting data migration from old CRM...');

        try {
            DB::beginTransaction();

            $this->migrateUsers();
            $this->migrateProgressCategories();
            $this->migrateDealerships();
            $this->migrateContacts();
            $this->migrateStores();
            $this->migrateProgresses();
            $this->migrateDealerEmailTemplates();
            $this->migrateDealerEmails();
            $this->migrateSentEmails();
            $this->migrateEmailTrackingEvents();
            $this->migratePdfAttachments();
            $this->migrateAttachables();
            $this->migrateReminders();
            $this->migrateTags();
            $this->migrateContactTag();
            $this->migrateDealershipUser();

            DB::commit();

            $this->info('Data migration completed successfully!');

            return Command::SUCCESS;
        } catch (Exception $e) {
            DB::rollBack();
            $this->error('Migration failed: '.$e->getMessage());
            $this->error($e->getTraceAsString());

            return Command::FAILURE;
        }
    }

    protected function truncateTables(): void
    {
        $this->info('Truncating tables...');

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $tables = [
            'dealership_user', 'contact_tag', 'attachables',
            'email_tracking_events', 'sent_emails', 'dealer_emails',
            'dealer_email_templates', 'progresses', 'stores',
            'contacts', 'dealerships', 'progress_categories',
            'reminders', 'tags', 'pdf_attachments', 'users',
        ];

        foreach ($tables as $table) {
            DB::table($table)->truncate();
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->info('Tables truncated.');
    }

    protected function migrateUsers(): void
    {
        $this->info('Migrating users...');

        $oldUsers = DB::connection('crm_old')->table('users')->get();

        $bar = $this->output->createProgressBar($oldUsers->count());
        $bar->start();

        foreach ($oldUsers as $user) {
            // Check if user has admin role
            $isAdmin = DB::connection('crm_old')
                ->table('model_has_roles')
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->where('model_has_roles.model_type', 'App\\Models\\User')
                ->where('model_has_roles.model_id', $user->id)
                ->where('roles.name', 'admin')
                ->exists();

            DB::table('users')->insert([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone ?? null,
                'email_verified_at' => $user->email_verified_at,
                'password' => $user->password,
                'timezone' => $user->timezone ?? null,
                'profile_photo_path' => $user->profile_photo_path ?? null,
                'is_admin' => $isAdmin,
                'two_factor_secret' => $user->two_factor_secret ?? null,
                'two_factor_recovery_codes' => $user->two_factor_recovery_codes ?? null,
                'two_factor_confirmed_at' => $user->two_factor_confirmed_at ?? null,
                'remember_token' => $user->remember_token,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
                'deleted_at' => $user->deleted_at ?? null,
            ]);

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Migrated {$oldUsers->count()} users.");
    }

    protected function migrateProgressCategories(): void
    {
        $this->info('Migrating progress categories...');

        $records = DB::connection('crm_old')->table('progress_categories')->get();

        foreach ($records as $record) {
            DB::table('progress_categories')->insert([
                'id' => $record->id,
                'name' => $record->name,
                'created_at' => $record->created_at,
                'updated_at' => $record->updated_at,
            ]);
        }

        $this->info("Migrated {$records->count()} progress categories.");
    }

    protected function migrateDealerships(): void
    {
        $this->info('Migrating dealerships...');

        $records = DB::connection('crm_old')->table('dealerships')->get();

        $bar = $this->output->createProgressBar($records->count());
        $bar->start();

        foreach ($records as $record) {
            DB::table('dealerships')->insert([
                'id' => $record->id,
                'user_id' => $record->user_id,
                'name' => $record->name,
                'address' => $record->address,
                'city' => $record->city,
                'state' => $record->state,
                'zip_code' => $record->zip_code,
                'phone' => $record->phone,
                'email' => $record->email,
                'current_solution_name' => $record->current_solution_name,
                'current_solution_use' => $record->current_solution_use,
                'notes' => $record->notes,
                'status' => $record->status,
                'rating' => $record->rating,
                'type' => $record->type,
                'in_development' => $record->in_development ?? false,
                'dev_status' => $record->dev_status,
                'created_at' => $record->created_at,
                'updated_at' => $record->updated_at,
            ]);

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Migrated {$records->count()} dealerships.");
    }

    protected function migrateContacts(): void
    {
        $this->info('Migrating contacts...');

        $records = DB::connection('crm_old')->table('contacts')->get();

        $bar = $this->output->createProgressBar($records->count());
        $bar->start();

        $skipped = 0;
        $migrated = 0;

        foreach ($records as $record) {
            // Check if dealership exists
            $dealershipExists = DB::table('dealerships')->where('id', $record->dealership_id)->exists();

            if (! $dealershipExists) {
                $skipped++;
                $bar->advance();

                continue;
            }

            DB::table('contacts')->insert([
                'id' => $record->id,
                'dealership_id' => $record->dealership_id,
                'name' => $record->name,
                'email' => $record->email,
                'phone' => $record->phone,
                'position' => $record->position,
                'linkedin_link' => $record->linkedin_link,
                'primary_contact' => $record->primary_contact ?? false,
                'created_at' => $record->created_at,
                'updated_at' => $record->updated_at,
            ]);

            $migrated++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Migrated {$migrated} contacts.");
        if ($skipped > 0) {
            $this->warn("Skipped {$skipped} orphaned contacts (dealership doesn't exist).");
        }
    }

    protected function migrateStores(): void
    {
        $this->info('Migrating stores...');

        $records = DB::connection('crm_old')->table('stores')->get();
        $skipped = 0;
        $migrated = 0;

        foreach ($records as $record) {
            // Check if user and dealership exist
            $userExists = DB::table('users')->where('id', $record->user_id)->exists();
            $dealershipExists = DB::table('dealerships')->where('id', $record->dealership_id)->exists();

            if (! $userExists || ! $dealershipExists) {
                $skipped++;

                continue;
            }

            DB::table('stores')->insert([
                'id' => $record->id,
                'user_id' => $record->user_id,
                'dealership_id' => $record->dealership_id,
                'name' => $record->name,
                'address' => $record->address,
                'city' => $record->city,
                'state' => $record->state,
                'zip_code' => $record->zip_code,
                'phone' => $record->phone,
                'current_solution_name' => $record->current_solution_name,
                'current_solution_use' => $record->current_solution_use,
                'notes' => $record->notes,
                'created_at' => $record->created_at,
                'updated_at' => $record->updated_at,
            ]);
            $migrated++;
        }

        $this->info("Migrated {$migrated} stores.");
        if ($skipped > 0) {
            $this->warn("Skipped {$skipped} orphaned stores.");
        }
    }

    protected function migrateProgresses(): void
    {
        $this->info('Migrating progresses...');

        $records = DB::connection('crm_old')->table('progresses')->get();
        $skipped = 0;
        $migrated = 0;

        foreach ($records as $record) {
            // Check if required foreign keys exist
            $userExists = DB::table('users')->where('id', $record->user_id)->exists();
            $dealershipExists = DB::table('dealerships')->where('id', $record->dealership_id)->exists();

            if (! $userExists || ! $dealershipExists) {
                $skipped++;

                continue;
            }

            // Contact and category are nullable, so check only if not null
            if ($record->contact_id && ! DB::table('contacts')->where('id', $record->contact_id)->exists()) {
                $record->contact_id = null;
            }

            if ($record->progress_category_id && ! DB::table('progress_categories')->where('id', $record->progress_category_id)->exists()) {
                $record->progress_category_id = null;
            }

            DB::table('progresses')->insert([
                'id' => $record->id,
                'user_id' => $record->user_id,
                'dealership_id' => $record->dealership_id,
                'contact_id' => $record->contact_id,
                'progress_category_id' => $record->progress_category_id,
                'details' => $record->details,
                'date' => $record->date,
                'created_at' => $record->created_at,
                'updated_at' => $record->updated_at,
            ]);
            $migrated++;
        }

        $this->info("Migrated {$migrated} progresses.");
        if ($skipped > 0) {
            $this->warn("Skipped {$skipped} orphaned progresses.");
        }
    }

    protected function migrateDealerEmailTemplates(): void
    {
        $this->info('Migrating dealer email templates...');

        $records = DB::connection('crm_old')->table('dealer_email_templates')->get();

        foreach ($records as $record) {
            DB::table('dealer_email_templates')->insert([
                'id' => $record->id,
                'name' => $record->name,
                'subject' => $record->subject,
                'body' => $record->body,
                'attachment_path' => $record->attachment_path,
                'attachment_name' => $record->attachment_name,
                'created_at' => $record->created_at,
                'updated_at' => $record->updated_at,
            ]);
        }

        $this->info("Migrated {$records->count()} dealer email templates.");
    }

    protected function migrateDealerEmails(): void
    {
        $this->info('Migrating dealer emails...');

        $records = DB::connection('crm_old')->table('dealer_emails')->get();
        $skipped = 0;
        $migrated = 0;

        foreach ($records as $record) {
            $userExists = DB::table('users')->where('id', $record->user_id)->exists();
            $dealershipExists = DB::table('dealerships')->where('id', $record->dealership_id)->exists();

            if (! $userExists || ! $dealershipExists) {
                $skipped++;

                continue;
            }

            // Template is nullable
            if ($record->dealer_email_template_id && ! DB::table('dealer_email_templates')->where('id', $record->dealer_email_template_id)->exists()) {
                $record->dealer_email_template_id = null;
            }

            DB::table('dealer_emails')->insert([
                'id' => $record->id,
                'user_id' => $record->user_id,
                'dealership_id' => $record->dealership_id,
                'dealer_email_template_id' => $record->dealer_email_template_id,
                'customize_email' => $record->customize_email ?? false,
                'customize_attachment' => $record->customize_attachment ?? false,
                'recipients' => $record->recipients,
                'attachment' => $record->attachment,
                'subject' => $record->subject,
                'message' => $record->message,
                'start_date' => $record->start_date,
                'last_sent' => $record->last_sent,
                'next_send_date' => $record->next_send_date,
                'frequency' => $record->frequency,
                'paused' => $record->paused ?? false,
                'created_at' => $record->created_at,
                'updated_at' => $record->updated_at,
            ]);
            $migrated++;
        }

        $this->info("Migrated {$migrated} dealer emails.");
        if ($skipped > 0) {
            $this->warn("Skipped {$skipped} orphaned dealer emails.");
        }
    }

    protected function migrateSentEmails(): void
    {
        $this->info('Migrating sent emails...');

        $records = DB::connection('crm_old')->table('sent_emails')->get();
        $skipped = 0;
        $migrated = 0;

        foreach ($records as $record) {
            $userExists = DB::table('users')->where('id', $record->user_id)->exists();
            $dealershipExists = DB::table('dealerships')->where('id', $record->dealership_id)->exists();

            if (! $userExists || ! $dealershipExists) {
                $skipped++;

                continue;
            }

            DB::table('sent_emails')->insert([
                'id' => $record->id,
                'user_id' => $record->user_id,
                'dealership_id' => $record->dealership_id,
                'recipient' => $record->recipient,
                'message_id' => $record->message_id,
                'subject' => $record->subject,
                'tracking_data' => $record->tracking_data,
                'created_at' => $record->created_at,
                'updated_at' => $record->updated_at,
            ]);
            $migrated++;
        }

        $this->info("Migrated {$migrated} sent emails.");
        if ($skipped > 0) {
            $this->warn("Skipped {$skipped} orphaned sent emails.");
        }
    }

    protected function migrateEmailTrackingEvents(): void
    {
        $this->info('Migrating email tracking events...');

        $records = DB::connection('crm_old')->table('email_tracking_events')->get();
        $skipped = 0;
        $migrated = 0;

        foreach ($records as $record) {
            if (! DB::table('sent_emails')->where('id', $record->sent_email_id)->exists()) {
                $skipped++;

                continue;
            }

            DB::table('email_tracking_events')->insert([
                'id' => $record->id,
                'sent_email_id' => $record->sent_email_id,
                'event_type' => $record->event_type,
                'message_id' => $record->message_id,
                'recipient_email' => $record->recipient_email,
                'url' => $record->url,
                'user_agent' => $record->user_agent,
                'ip_address' => $record->ip_address,
                'mailgun_data' => $record->mailgun_data,
                'event_timestamp' => $record->event_timestamp,
                'created_at' => $record->created_at,
                'updated_at' => $record->updated_at,
            ]);
            $migrated++;
        }

        $this->info("Migrated {$migrated} email tracking events.");
        if ($skipped > 0) {
            $this->warn("Skipped {$skipped} orphaned email tracking events.");
        }
    }

    protected function migratePdfAttachments(): void
    {
        $this->info('Migrating PDF attachments...');

        $records = DB::connection('crm_old')->table('pdf_attachments')->get();

        foreach ($records as $record) {
            DB::table('pdf_attachments')->insert([
                'id' => $record->id,
                'file_name' => $record->file_name,
                'file_path' => $record->file_path,
                'created_at' => $record->created_at,
                'updated_at' => $record->updated_at,
            ]);
        }

        $this->info("Migrated {$records->count()} PDF attachments.");
    }

    protected function migrateAttachables(): void
    {
        $this->info('Migrating attachables...');

        $records = DB::connection('crm_old')->table('attachables')->get();
        $bar = $this->output->createProgressBar($records->count());
        $bar->start();

        $skipped = 0;
        $migrated = 0;

        foreach ($records as $record) {
            // Check if pdf_attachment exists
            $pdfAttachmentExists = DB::table('pdf_attachments')->where('id', $record->pdf_attachment_id)->exists();

            if (! $pdfAttachmentExists) {
                $skipped++;
                $bar->advance();

                continue;
            }

            DB::table('attachables')->insert([
                'id' => $record->id,
                'pdf_attachment_id' => $record->pdf_attachment_id,
                'attachable_id' => $record->attachable_id,
                'attachable_type' => $record->attachable_type,
                'created_at' => $record->created_at,
                'updated_at' => $record->updated_at,
            ]);

            $migrated++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();

        $this->info("Migrated {$migrated} attachables.");

        if ($skipped > 0) {
            $this->warn("Skipped {$skipped} orphaned attachables (PDF attachment doesn't exist).");
        }
    }

    protected function migrateReminders(): void
    {
        $this->info('Migrating reminders...');

        $records = DB::connection('crm_old')->table('reminders')->get();

        foreach ($records as $record) {
            DB::table('reminders')->insert([
                'id' => $record->id,
                'user_id' => $record->user_id,
                'dev_rel' => $record->dev_rel,
                'title' => $record->title,
                'message' => $record->message,
                'start_date' => $record->start_date,
                'last_sent' => $record->last_sent,
                'sending_frequency' => $record->sending_frequency,
                'pause' => $record->pause ?? false,
                'created_at' => $record->created_at,
                'updated_at' => $record->updated_at,
            ]);
        }

        $this->info("Migrated {$records->count()} reminders.");
    }

    protected function migrateTags(): void
    {
        $this->info('Migrating tags...');

        $records = DB::connection('crm_old')->table('tags')->get();

        foreach ($records as $record) {
            DB::table('tags')->insert([
                'id' => $record->id,
                'name' => $record->name,
                'created_at' => $record->created_at,
                'updated_at' => $record->updated_at,
            ]);
        }

        $this->info("Migrated {$records->count()} tags.");
    }

    protected function migrateContactTag(): void
    {
        $this->info('Migrating contact_tag pivot...');

        $records = DB::connection('crm_old')->table('contact_tag')->get();
        $skipped = 0;
        $migrated = 0;

        foreach ($records as $record) {
            $contactExists = DB::table('contacts')->where('id', $record->contact_id)->exists();
            $tagExists = DB::table('tags')->where('id', $record->tag_id)->exists();

            if (! $contactExists || ! $tagExists) {
                $skipped++;

                continue;
            }

            DB::table('contact_tag')->insert([
                'contact_id' => $record->contact_id,
                'tag_id' => $record->tag_id,
            ]);
            $migrated++;
        }

        $this->info("Migrated {$migrated} contact_tag relationships.");
        if ($skipped > 0) {
            $this->warn("Skipped {$skipped} orphaned contact_tag relationships.");
        }
    }

    protected function migrateDealershipUser(): void
    {
        $this->info('Migrating dealership_user pivot...');

        $records = DB::connection('crm_old')->table('dealership_user')->get();
        $skipped = 0;
        $duplicates = 0;
        $migrated = 0;

        foreach ($records as $record) {
            $dealershipExists = DB::table('dealerships')->where('id', $record->dealership_id)->exists();
            $userExists = DB::table('users')->where('id', $record->user_id)->exists();

            if (! $dealershipExists || ! $userExists) {
                $skipped++;

                continue;
            }

            // Check if this relationship already exists
            $alreadyExists = DB::table('dealership_user')
                ->where('dealership_id', $record->dealership_id)
                ->where('user_id', $record->user_id)
                ->exists();

            if ($alreadyExists) {
                $duplicates++;

                continue;
            }

            DB::table('dealership_user')->insert([
                'dealership_id' => $record->dealership_id,
                'user_id' => $record->user_id,
            ]);
            $migrated++;
        }

        $this->info("Migrated {$migrated} dealership_user relationships.");
        if ($skipped > 0) {
            $this->warn("Skipped {$skipped} orphaned dealership_user relationships.");
        }
        if ($duplicates > 0) {
            $this->warn("Skipped {$duplicates} duplicate dealership_user relationships.");
        }
    }
}
