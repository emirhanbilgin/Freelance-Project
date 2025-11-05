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
        
        // Bugünün İstanbul zamanına göre UTC aralığını hesapla
        $istanbulNow = Carbon::now('Europe/Istanbul');
        $startUtc = $istanbulNow->copy()->startOfDay()->setTimezone('UTC');
        $endUtc = $istanbulNow->copy()->setTimezone('UTC');
        $resetTime = $istanbulNow->copy()->setTime(17, 0, 0); // Akşam 5 (IST)

        $openReceipts = Receipt::where('daily_reset', false)
            ->whereBetween('created_at', [$startUtc, $endUtc])
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
