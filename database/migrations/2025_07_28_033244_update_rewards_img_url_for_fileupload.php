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
        // For now, we'll keep the existing external URLs as they are
        // The FileUpload component will handle new uploads
        // Existing rewards with external URLs will continue to work
        // Users can manually update them to use FileUpload if needed
        
        // No changes needed to the database structure
        // The img_url field can store both external URLs and file paths
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No changes to reverse
    }
};
