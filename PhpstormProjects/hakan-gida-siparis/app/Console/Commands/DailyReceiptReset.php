<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Receipt;
use Carbon\Carbon;

class DailyReceiptReset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'receipts:daily-reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Akşam 5\'te açık fişleri arşivler ve yeni gün için hazırlar';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Günlük fiş sıfırlama başlatılıyor...');
        
        // Bugün oluşturulan fişleri bul
        $today = Carbon::now()->setTimezone('Europe/Istanbul')->startOfDay();
        $resetTime = Carbon::now()->setTimezone('Europe/Istanbul')->setTime(17, 0, 0); // Akşam 5
        
        $openReceipts = Receipt::where('daily_reset', false)
            ->whereDate('created_at', $today)
            ->get();
        
        $archivedCount = 0;
        
        foreach ($openReceipts as $receipt) {
            // Fişi arşivle
            $receipt->update([
                'archived_at' => $resetTime,
                'daily_reset' => true
            ]);
            
            $archivedCount++;
        }
        
        $this->info("Toplam {$archivedCount} fiş arşivlendi.");
        $this->info('Günlük fiş sıfırlama tamamlandı.');
        
        return 0;
    }
}
