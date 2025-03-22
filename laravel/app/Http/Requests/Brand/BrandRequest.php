<?php

namespace App\Http\Requests\Brand;

use Illuminate\Foundation\Http\FormRequest;

class BrandRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('brand', null);

        return [
            'name' => 'required|unique:brands,name,' . $id,
            'slug' => 'nullable|unique:brands,slug,' . $id,
            'description' => 'nullable|string',
            'logo' => 'nullable|string',
            'website_url' => 'nullable|string',
            'seo_title' => 'nullable|string',
            'seo_description' => 'nullable|string|max:150',
            'seo_keywords' => 'nullable|array',
            'seo_tags' => 'nullable|array',
            'published' => 'required|in:1,2'
        ];
    }

    public function messages()
    {
        return __('request.messages');
    }

    public function attributes()
    {
        return [
            'name' => 'Tên thương hiệu',
            'slug' => 'Slug',
            'description' => 'Mô tả',
            'logo' => 'Hình ảnh'
        ];
    }
}
