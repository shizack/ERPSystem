<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
{
    $productId = $this->route('product') ? $this->route('product')->product_id : null;
    
    return [
        'name' => 'required|string|max:255',
        'product_id' => [
            'required',
            'integer',
            'min:1',
            Rule::unique('products', 'product_id')->ignore($productId, 'product_id')
        ],
        'category' => 'required|string|max:255',
        'supplier_id' => 'nullable|exists:suppliers,id',
        'buying_price' => 'required|numeric|min:0',
        'quantity' => 'required|integer|min:0',
        'unit' => 'required|string|max:50',
        'threshold_value' => 'required|integer|min:0',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'remove_image' => 'nullable|boolean'
    ];
}
}