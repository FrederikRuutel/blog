<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique();
        });

        DB::table('users')->select('id', 'name')->chunkById(500, function ($users) {
            foreach ($users as $user) {
                $base = Str::substr(Str::slug($user->name) ?: 'user', 0, 240);
                $slug = $base;

                for ($suffix = 1; DB::table('users')->where('slug', $slug)->exists(); $suffix++) {
                    $slug = $base.'-'.$suffix;
                }

                DB::table('users')->where('id', $user->id)->update(['slug' => $slug]);
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });
    }
};
