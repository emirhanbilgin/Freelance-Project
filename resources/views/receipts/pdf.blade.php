<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Fiş #{{ $receipt->id }}</title>
    <style>
        @page {
            size: 80mm 200mm;
            margin: 5mm;
        }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            line-height: 1.2;
            margin: 0;
            padding: 0;
        }
        .receipt-header {
            text-align: center;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        .receipt-title {
            font-size: 14px;
            font-weight: bold;
            margin: 0;
        }
        .receipt-subtitle {
            font-size: 10px;
            margin: 2px 0;
        }
        .info {
            margin-bottom: 8px;
        }
        .info p {
            margin: 1px 0;
            font-size: 9px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            font-size: 8px;
        }
        th, td {
            border: 1px solid #000;
            padding: 2px;
            text-align: left;
        }
        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .total-row {
            font-weight: bold;
            background-color: #f0f0f0;
        }
        .footer {
            margin-top: 10px;
            text-align: center;
            font-size: 8px;
            border-top: 1px solid #000;
            padding-top: 5px;
        }
        .payment-info {
            margin-top: 5px;
            font-size: 9px;
            text-align: center;
        }
        @media print {
            body { margin: 0; }
        }
    </style>
</head>
<body>

<div class="receipt-header">
    <h1 class="receipt-title">HAKAN GIDA</h1>
    <p class="receipt-subtitle">Fiş #{{ $receipt->id }}</p>
</div>

<div class="info">
    <p><strong>Müşteri:</strong> {{ $receipt->customer->name }}</p>
    <p><strong>Tarih:</strong> {{ $receipt->created_at->format('d.m.Y H:i') }}</p>
    @if($receipt->description)
        <p><strong>Açıklama:</strong> {{ $receipt->description }}</p>
    @endif
</div>

<table>
    <thead>
    <tr>
        <th>Ürün</th>
        <th>Miktar</th>
        <th>Birim Fiyat</th>
        <th>Tutar</th>
        <th>Not</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($receipt->items as $item)
        @php $subtotal = $item->price * $item->quantity; @endphp
        <tr>
            <td>{{ $item->product->name }}</td>
            <td>{{ $item->quantity }}</td>
            <td>{{ number_format($item->price, 2, ',', '.') }} ₺</td>
            <td>{{ number_format($subtotal, 2, ',', '.') }} ₺</td>
            <td>{{ $item->note ?? '-' }}</td>
        </tr>
    @endforeach
    @if($receipt->payment_method === 'credit_card')
        <tr>
            <td colspan="3" style="text-align:right;">Ara Toplam:</td>
            <td colspan="2">{{ number_format($receipt->calculateSubtotal(), 2, ',', '.') }} ₺</td>
        </tr>
        <tr>
            <td colspan="3" style="text-align:right;">Kredi Kartı Komisyonu (%1):</td>
            <td colspan="2">+{{ number_format($receipt->calculateCommission(), 2, ',', '.') }} ₺</td>
        </tr>
        <tr class="total-row">
            <td colspan="3" style="text-align:right;"><strong>TOPLAM:</strong></td>
            <td colspan="2"><strong>{{ number_format($receipt->calculateTotalAmount(), 2, ',', '.') }} ₺</strong></td>
        </tr>
    @else
        <tr class="total-row">
            <td colspan="3" style="text-align:right;"><strong>TOPLAM:</strong></td>
            <td colspan="2"><strong>{{ number_format($receipt->calculateTotalAmount(), 2, ',', '.') }} ₺</strong></td>
        </tr>
    @endif
    </tbody>
</table>

<div class="payment-info">
    @if($receipt->payment_method === 'cash')
        <p><strong>Ödeme: 💵 NAKİT (Komisyon Yok)</strong></p>
    @elseif($receipt->payment_method === 'credit_card')
        <p><strong>Ödeme: 💳 KREDİ KARTI (+%1 Komisyon)</strong></p>
    @else
        <p><strong>Ödeme: 📝 VERESİYE (Ödeme Sonra)</strong></p>
    @endif
</div>

<div class="footer">
    <p>Bu belge sistem üzerinden oluşturulmuştur.</p>
    <p>Hakan Gıda - Geçerlidir</p>
</div>

</body>
</html>
