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
        Schema::table('m_profile_perusahaan', function (Blueprint $table) {
            // Add missing columns for services and features
            $table->text('services_subtitle')->nullable();
            $table->string('features_title', 255)->nullable();
            $table->json('features')->nullable();

            // Add social media URLs
            $table->string('facebook_url', 255)->nullable();
            $table->string('instagram_url', 255)->nullable();
            $table->string('twitter_url', 255)->nullable();

            // Add footer description and reviews
            $table->text('footer_description')->nullable();
            $table->json('reviews')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('m_profile_perusahaan', function (Blueprint $table) {
            $table->dropColumn([
                'services_subtitle',
                'features_title',
                'features',
                'facebook_url',
                'instagram_url',
                'twitter_url',
                'footer_description',
                'reviews'
            ]);
        });
    }
};
