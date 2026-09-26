<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'content';

    public function up(): void
    {
        Schema::connection($this->connection)->create('faqs', function (Blueprint $table): void {
            $table->id();
            $table->string('question', 500);
            $table->text('answer');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        DB::connection($this->connection)->table('faqs')->insert([
            [
                'question' => 'What is King Lotus International hotel share ownership?',
                'answer' => 'It is an exclusive opportunity to purchase fractional ownership shares in prime luxury hotel and resort developments. Shareholders own certified property equity and earn proportional revenue from hotel operations without the burden of daily management.',
                'sort_order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question' => 'How and when do shareholders receive profits or dividends?',
                'answer' => 'Dividend returns are generated from hotel room bookings, dining, events, and overall resort operations. Audited earnings are distributed directly to each shareholder\'s registered bank account on a structured annual or bi-annual schedule, backed by transparent financial reporting.',
                'sort_order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question' => 'Are shareholders entitled to complimentary room stays and discounts?',
                'answer' => 'Yes. Every valued shareholder enjoys dedicated complimentary room nights annually, priority suite reservations, VIP lounge access, and exclusive discounts on resort dining, spa services, and events across all King Lotus properties.',
                'sort_order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question' => 'What legal documents guarantee my share investment?',
                'answer' => 'Every shareholder receives an official, legally notarized Share Ownership Certificate and investment deed registered under King Lotus International, alongside verified credentials for the online Shareholder Portal to track investment valuation and updates.',
                'sort_order' => 4,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question' => 'Can I transfer, sell, or pass my shares to my family or nominees?',
                'answer' => 'Absolutely. King Lotus hotel shares are legally recognized, transferable assets. You may transfer ownership, assign designated nominees or legal heirs, or sell shares through our official shareholder buy-back and exchange program.',
                'sort_order' => 5,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('faqs');
    }
};
