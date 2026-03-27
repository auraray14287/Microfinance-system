<div>
    <div class="w-full overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                <tr>
                    <th class="px-4 py-2 text-left font-semibold text-gray-600 dark:text-gray-300">Product</th>
                    <th class="px-4 py-2 text-left font-semibold text-gray-600 dark:text-gray-300 w-24">Qty</th>
                    <th class="px-4 py-2 text-left font-semibold text-gray-600 dark:text-gray-300 w-36">Unit Price (KES)</th>
                    <th class="px-4 py-2 text-left font-semibold text-gray-600 dark:text-gray-300 w-36">Subtotal (KES)</th>
                    <th class="px-4 py-2 w-10"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach($rows as $i => $row)
                <tr class="bg-white dark:bg-gray-900" wire:key="row-{{ $i }}">
                    <td class="px-4 py-2">
                        <select
                            wire:change="selectProduct({{ $i }}, $event.target.value)"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-1.5 text-sm dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                        >
                            <option value="">Select product...</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" @selected($row['product_id'] == $product->id)>
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td class="px-4 py-2">
                        <input
                            type="number"
                            wire:change="updateQuantity({{ $i }}, $event.target.value)"
                            min="1"
                            value="{{ $row['quantity'] ?? 1 }}"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-1.5 text-sm dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-primary-500"
                        />
                    </td>
                    <td class="px-4 py-2">
                        <input type="text" readonly
                            value="{{ number_format($row['unit_price'] ?? 0, 2) }}"
                            class="w-full border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-1.5 text-sm bg-gray-50 dark:bg-gray-800 dark:text-gray-400 cursor-not-allowed"
                        />
                    </td>
                    <td class="px-4 py-2">
                        <input type="text" readonly
                            value="{{ number_format($row['subtotal'] ?? 0, 2) }}"
                            class="w-full border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-1.5 text-sm bg-gray-50 dark:bg-gray-800 dark:text-gray-400 cursor-not-allowed font-semibold"
                        />
                    </td>
                    <td class="px-4 py-2 text-center">
                        @if(count($rows) > 1)
                        <button type="button"
                            wire:click="removeRow({{ $i }})"
                            class="text-red-500 hover:text-red-700 transition p-1 rounded">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-gray-50 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
                <tr>
                    <td colspan="3" class="px-4 py-2 text-right font-semibold text-gray-700 dark:text-gray-300 text-sm">Total Principal:</td>
                    <td class="px-4 py-2 font-bold text-green-600 dark:text-green-400 text-sm">KES {{ number_format($principal, 2) }}</td>
                    <td class="px-4 py-2 text-right">
                        <button type="button"
                            wire:click="addRow"
                            class="inline-flex items-center gap-1 px-3 py-1.5 bg-primary-600 hover:bg-primary-700 text-white text-xs font-semibold rounded-lg transition shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Add
                        </button>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
