<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ __('Wallets') }}</h2>
            <a href="javascript:;" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 dark:text-gray-400 hover:border-gray-400 hover:text-gray-800 dark:hover:border-gray-800 dark:hover:text-gray-200 openWalletModal">
                {{ __('Create Wallet') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <table class="table table-bordered data-table" id="wallets-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Name</th>
                                <th>Slug</th>
                                <th>Balance</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

@include('wallets.modals.modal')
@include('wallets.modals.cardpreview')

@push('scripts')
<script>
$("body").on("click", ".openWalletModal", function() {
    $("body").find("#createWalletModal").modal("show");
});

$("body").on("click", ".openCardPreviewModal", function() {
    $("body").find("#cardPreviewModal").modal("show");
});


$(document).ready(function() {
    $('#wallets-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("wallets.data") }}',
        columns: [
            { data: 'id' },
            { data: 'user', name: 'holder.name' },
            { data: 'name' },
            { data: 'slug' },
            { data: 'balance' },
            { data: 'actions', orderable: false, searchable: false }
        ]
    });
});
</script>