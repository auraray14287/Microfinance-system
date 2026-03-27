@livewire('loan-product-table', [], key('loan-product-table'))

<div
    x-data
    x-on:loan-products-updated.window="
        $wire.set('data.principal_calc', $event.detail[0].principal);
        $wire.set('data.principal_amount', $event.detail[0].principal);
        $wire.set('data.product_description', $event.detail[0].description);
    "
></div>
