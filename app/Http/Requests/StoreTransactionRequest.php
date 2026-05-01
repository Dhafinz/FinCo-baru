<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:500',
            'transaction_date' => 'required|date|before_or_equal:today',
        ];

        // For expense mode with budget selection
        if ($this->input('type') === 'expense' && $this->filled('budget_id')) {
            $rules['budget_id'] = [
                'required',
                Rule::exists('budgets', 'id')->where(function ($query) {
                    return $query->where('user_id', $this->user()->id)->where('is_active', true);
                }),
            ];
            $rules['category_id'] = 'nullable'; // Category auto-filled from budget
        } else {
            // General mode: category must match type
            $rules['category_id'] = [
                'required',
                Rule::exists('categories', 'id')->where(function ($query) {
                    return $query->where('type', $this->input('type'));
                }),
            ];
        }

        // For dedicated income flow (mode=income) require goal selection
        if ($this->input('mode') === 'income') {
            $rules['goal_id'] = [
                'required',
                Rule::exists('financial_goals', 'id')->where(function ($query) {
                    return $query->where('user_id', $this->user()->id)->where('status', 'active');
                }),
            ];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Kategori harus dipilih',
            'category_id.exists' => 'Kategori tidak sesuai dengan tipe transaksi',
            'type.required' => 'Tipe transaksi harus dipilih',
            'amount.required' => 'Jumlah harus diisi',
            'transaction_date.required' => 'Tanggal transaksi harus diisi',
            'budget_id.required' => 'Pilih budget terlebih dahulu',
            'budget_id.exists' => 'Budget tidak ditemukan atau tidak aktif',
            'goal_allocations.*.goal_id.required' => 'Pilih goal untuk alokasi',
            'goal_allocations.*.goal_id.exists' => 'Goal tidak ditemukan atau tidak aktif',
            'goal_allocations.*.allocated_amount.required' => 'Masukkan jumlah alokasi',
            'goal_allocations.*.allocated_amount.min' => 'Jumlah alokasi minimal Rp 0,01',
            'goal_id.required' => 'Pilih goal terlebih dahulu',
            'goal_id.exists' => 'Goal tidak ditemukan atau tidak aktif',
            'goal_allocations.total' => 'Total alokasi goal tidak boleh melebihi total income',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // only validate allocations/goal when in dedicated income mode
            if ($this->input('mode') !== 'income') {
                return;
            }

            $goalId = $this->input('goal_id');
            if (! $goalId) {
                $validator->errors()->add('goal_id', 'Pilih goal terlebih dahulu');
            }
        });
    }
}