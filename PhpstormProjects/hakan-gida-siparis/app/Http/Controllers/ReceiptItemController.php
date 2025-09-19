<?php

namespace App\Http\Controllers;

use App\Models\ReceiptItem;
use Illuminate\Http\Request;

class ReceiptItemController extends Controller
{
    public function update(Request $request, $id)
    {
        $request->validate([
            'price' => 'required|numeric|min:0',
        ]);

        $item = ReceiptItem::findOrFail($id);
        $item->price = $request->price;
        $item->save();

        return back()->with('success', 'Fiyat güncellendi.');
    }
    public function store(Request $request, $receiptId)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'note' => 'nullable|string|max:1000',
        ]);

        \App\Models\ReceiptItem::create([
            'receipt_id' => $receiptId,
            'product_id' => $request->input('product_id'),
            'quantity' => $request->input('quantity'),
            'price' => $request->input('price'),
            'note' => isset($request['note']) ? $request['note'] : null, // coalesce yerine isset
        ]);

        return redirect()
            ->route('receipts.edit', $receiptId)
            ->with('success', 'Ürün başarıyla eklendi.');
    }

}
