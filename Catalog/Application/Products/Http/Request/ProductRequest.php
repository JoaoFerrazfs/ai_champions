<?php

namespace Catalog\Application\Products\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
	public function authorize(): bool
	{
		return true;
	}

	public function rules(): array
	{
		return [
			'name' => 'required|string|max:255',
			'description' => 'required|string|max:1000',
			'price' => 'required|numeric|min:0|regex:/^\d+(\.\d{1,2})?$/',
		];
	}
}
