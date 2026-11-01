@extends('layouts.admin')
@section('title',isset($coupon)?'Edit Coupon':'Add Coupon')
@section('page-title',isset($coupon)?'Edit Coupon':'Add Coupon')
@section('content')
<div class="bg-white border border-gray-200 rounded-lg p-6 max-w-2xl shadow-sm">
    <form action="{{ isset($coupon) ? route('admin.coupons.update',$coupon) : route('admin.coupons.store') }}" method="POST" class="space-y-6">
        @csrf
        @if(isset($coupon)) @method('PUT') @endif
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-xs font-medium text-gray-700 mb-1">Coupon Code *</label>
                <input type="text" name="code" value="{{ old('code',$coupon->code??'') }}" required class="w-full bg-white border border-gray-300 rounded px-4 py-2 text-sm focus:outline-none focus:border-primary uppercase font-mono">
                <p class="text-[10px] text-gray-500 mt-1">E.g., SUMMERSALE20</p>
            </div>
            
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Discount Type *</label>
                <select name="type" required class="w-full bg-white border border-gray-300 rounded px-4 py-2 text-sm focus:outline-none focus:border-primary">
                    <option value="fixed" {{ old('type',$coupon->type??'')=='fixed'?'selected':'' }}>Fixed Amount</option>
                    <option value="percent" {{ old('type',$coupon->type??'')=='percent'?'selected':'' }}>Percentage</option>
                </select>
            </div>
            
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Discount Value *</label>
                <input type="number" step="0.01" name="value" value="{{ old('value',$coupon->value??'') }}" required class="w-full bg-white border border-gray-300 rounded px-4 py-2 text-sm focus:outline-none focus:border-primary">
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Minimum Order Amount</label>
                <input type="number" step="0.01" name="min_amount" value="{{ old('min_amount',$coupon->min_amount??'') }}" class="w-full bg-white border border-gray-300 rounded px-4 py-2 text-sm focus:outline-none focus:border-primary">
                <p class="text-[10px] text-gray-500 mt-1">Leave empty for no minimum</p>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Usage Limit</label>
                <input type="number" name="usage_limit" value="{{ old('usage_limit',$coupon->usage_limit??'') }}" class="w-full bg-white border border-gray-300 rounded px-4 py-2 text-sm focus:outline-none focus:border-primary">
                <p class="text-[10px] text-gray-500 mt-1">Total times this coupon can be used across all users</p>
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-medium text-gray-700 mb-1">Expiration Date</label>
                <input type="datetime-local" name="expires_at" value="{{ old('expires_at', isset($coupon) && $coupon->expires_at ? $coupon->expires_at->format('Y-m-d\TH:i') : '') }}" class="w-full bg-white border border-gray-300 rounded px-4 py-2 text-sm focus:outline-none focus:border-primary">
            </div>

            <div class="md:col-span-2 flex items-center gap-2 border-t border-gray-100 pt-4">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active',$coupon->is_active??true)?'checked':'' }} class="w-4 h-4 accent-primary">
                <span class="text-sm font-medium text-gray-700">Coupon is Active</span>
            </div>
        </div>
        
        <div class="pt-4 border-t border-gray-100 flex gap-4">
            <button type="submit" class="bg-primary hover:bg-primary-hover text-white px-6 py-2.5 rounded text-sm font-bold transition shadow-sm uppercase tracking-wide">Save Coupon</button>
            <a href="{{ route('admin.coupons.index') }}" class="bg-gray-100 border border-gray-300 hover:bg-gray-200 text-gray-700 px-6 py-2.5 rounded text-sm font-bold transition uppercase tracking-wide">Cancel</a>
        </div>
    </form>
</div>
@endsection
