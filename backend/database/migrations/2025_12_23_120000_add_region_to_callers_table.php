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
        Schema::table('callers', function (Blueprint $table) {
            // Add region column
            $table->string('region')->nullable()->after('rtom');
            
            // Update index to include region
            $table->index(['region', 'rtom', 'status']);
        });
        
        // Update existing callers to have regions based on their RTOM
        // This maps RTOM codes to their regions
        $rtomToRegionMap = [
            // Metro Region
            'CO' => 'Metro Region', 'MA' => 'Metro Region', 'ND' => 'Metro Region',
            'HK' => 'Metro Region', 'KX' => 'Metro Region', 'WT' => 'Metro Region',
            'RM' => 'Metro Region',
            
            // Region 1
            'AN' => 'Region 1', 'CW' => 'Region 1', 'GP' => 'Region 1',
            'KA' => 'Region 1', 'KU' => 'Region 1', 'MT' => 'Region 1',
            'NE' => 'Region 1', 'PO' => 'Region 1', 'KI' => 'Region 1',
            
            // Region 2
            'AV' => 'Region 2', 'BA' => 'Region 2', 'BW' => 'Region 2',
            'GA' => 'Region 2', 'HB' => 'Region 2', 'HA' => 'Region 2',
            'KL' => 'Region 2', 'KG' => 'Region 2', 'MA' => 'Region 2',
            'NE' => 'Region 2', 'RA' => 'Region 2',
            
            // Region 3
            'AM' => 'Region 3', 'BT' => 'Region 3', 'JA' => 'Region 3',
            'KM' => 'Region 3', 'KO' => 'Region 3', 'TR' => 'Region 3',
            'VU' => 'Region 3',
        ];
        
        // Update existing callers
        foreach ($rtomToRegionMap as $rtom => $region) {
            DB::table('callers')
                ->where('rtom', $rtom)
                ->update(['region' => $region]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('callers', function (Blueprint $table) {
            $table->dropIndex(['region', 'rtom', 'status']);
            $table->dropColumn('region');
        });
    }
};
