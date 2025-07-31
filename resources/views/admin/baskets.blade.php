@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-3xl font-bold mb-6">Baskets Dashboard</h1>
    <form method="GET" class="mb-4 flex items-center">
        <label for="filter" class="mr-2 font-medium">Filter by Status:</label>
        <select id="filter" name="is_draft" class="border rounded px-2 py-1" onchange="this.form.submit()">
            <option value="">All</option>
            <option value="1" @if(request('is_draft')==='1') selected @endif>Draft</option>
            <option value="0" @if(request('is_draft')==='0') selected @endif>Confirmed</option>
        </select>
    </form>
    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700">User</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700">Items (JSON)</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700">Total Price</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700">Discount Code</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700">is_draft</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($baskets as $basket)
                <tr>
                    <td class="px-4 py-2">{{ $basket->user ? $basket->user->email : 'Guest' }}</td>
                    <td class="px-4 py-2 text-xs font-mono whitespace-pre">{{ json_encode($basket->items, JSON_PRETTY_PRINT) }}</td>
                    <td class="px-4 py-2">${{ number_format($basket->total_price, 2) }}</td>
                    <td class="px-4 py-2">{{ $basket->discount_code ?? '-' }}</td>
                    <td class="px-4 py-2">
                        @if($basket->is_draft)
                            <span class="inline-block px-2 py-1 rounded text-xs font-bold bg-yellow-100 text-yellow-800">Draft</span>
                        @else
                            <span class="inline-block px-2 py-1 rounded text-xs font-bold bg-green-100 text-green-800">Confirmed</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-2 text-center text-gray-500">No baskets found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $baskets->links() }}</div>
</div>
@endsection 