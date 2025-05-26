@extends('components.header')
@section('content')

<main class="flex-1 flex-row p-8 overflow-y-auto custom-scrollbar">
    @include('components.sidebar')
    <div class="bg-white p-6 rounded-2xl shadow-md">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Recent Sales Transactions</h2>

        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Transaction ID
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Date
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Item
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Quantity
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Amount
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#TRN001</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2024-05-20</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Premium Subscription</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">1</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-semibold">$99.99</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Completed
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#TRN002</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2024-05-18</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Financial Planning Session</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">1</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-semibold">$249.00</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Completed
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#TRN003</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2024-05-15</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Investment Course</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">1</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-semibold">$49.99</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Completed
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#TRN004</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2024-05-12</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Basic Account Upgrade</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">1</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 font-semibold">-$10.00</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                Refunded
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#TRN005</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2024-05-10</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Financial E-book</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">1</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-semibold">$19.99</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Completed
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</main>
@endsection