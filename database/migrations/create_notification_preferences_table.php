<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create($this->table(), function (Blueprint $table): void {
            $table->id();
            $table->morphs('notifiable');
            $table->string('group');
            $table->string('channel');
            $table->boolean('enabled');
            $table->timestamps();

            $table->unique(
                ['notifiable_type', 'notifiable_id', 'group', 'channel'],
                'notify_matrix_unique',
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->table());
    }

    private function table(): string
    {
        return (string) config('notify-matrix.table', 'notification_preferences');
    }
};
