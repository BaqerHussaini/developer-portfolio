<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('projects', 'sections')) {
            return;
        }

        DB::table('projects')
            ->whereNotNull('sections')
            ->get(['id', 'description', 'sections'])
            ->each(function ($project) {
                $sections = json_decode($project->sections, true);

                if (! is_array($sections)) {
                    return;
                }

                $blocks = filled($project->description) ? [trim($project->description)] : [];

                foreach ($sections as $section) {
                    if (! is_array($section)) {
                        continue;
                    }

                    $heading = trim((string) ($section['heading'] ?? ''));
                    $body = trim((string) ($section['body'] ?? ''));

                    if ($heading === '' && $body === '') {
                        continue;
                    }

                    $blocks[] = ($heading !== '' ? '## '.$heading."\n\n" : '').$body;
                }

                DB::table('projects')->where('id', $project->id)->update([
                    'description' => implode("\n\n", array_filter($blocks)) ?: null,
                ]);
            });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('sections');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->jsonb('sections')->nullable()->after('description');
        });
    }
};
