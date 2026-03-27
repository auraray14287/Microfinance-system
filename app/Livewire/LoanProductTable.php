<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;

class LoanProductTable extends Component
{
    public array $rows = [];
    public float $principal = 0;
    public string $description = '';

    public function mount(): void
    {
        $this->rows = [[
            'product_id' => '',
            'quantity'   => 1,
            'unit_price' => 0,
            'subtotal'   => 0,
            'name'       => '',
        ]];
    }

    public function addRow(): void
    {
        $this->rows[] = ['product_id' => '', 'quantity' => 1, 'unit_price' => 0, 'subtotal' => 0, 'name' => ''];
        $this->recalculate();
    }

    public function removeRow(int $index): void
    {
        if (count($this->rows) > 1) {
            array_splice($this->rows, $index, 1);
            $this->rows = array_values($this->rows);
            $this->recalculate();
        }
    }

    public function selectProduct(int $index, $productId): void
    {
        if (!empty($productId)) {
            $product = Product::find($productId);
            if ($product) {
                $this->rows[$index]['product_id'] = $productId;
                $this->rows[$index]['unit_price'] = (float)$product->price;
                $this->rows[$index]['name']       = $product->description ?? $product->name;
                $qty = (float)($this->rows[$index]['quantity'] ?? 1);
                $this->rows[$index]['subtotal']   = round($qty * (float)$product->price, 2);
            }
        } else {
            $this->rows[$index]['product_id'] = '';
            $this->rows[$index]['unit_price'] = 0;
            $this->rows[$index]['subtotal']   = 0;
            $this->rows[$index]['name']       = '';
        }
        $this->recalculate();
    }

    public function updateQuantity(int $index, $quantity): void
    {
        $this->rows[$index]['quantity'] = (int)$quantity;
        $price = (float)($this->rows[$index]['unit_price'] ?? 0);
        $this->rows[$index]['subtotal'] = round((float)$quantity * $price, 2);
        $this->recalculate();
    }

    private function recalculate(): void
    {
        $this->principal = collect($this->rows)->sum(fn($r) => (float)($r['subtotal'] ?? 0));
        $this->description = collect($this->rows)
            ->filter(fn($r) => !empty($r['product_id']))
            ->map(fn($r) => ($r['quantity'] ?? 1) . 'x ' . ($r['name'] ?? ''))
            ->implode(', ');

        $this->dispatch('loan-products-updated',
            principal: $this->principal,
            description: $this->description,
        );
    }

    public function render()
    {
        $products = Product::where('status', 'active')->orderBy('name')->get();
        return view('livewire.loan-product-table', ['products' => $products]);
    }
}
