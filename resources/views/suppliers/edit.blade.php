@extends('layouts.app')

@section('title', 'Edit Supplier')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
    body { background-color: #021829; font-family: 'DM Sans', sans-serif; }
    .gradient-text {
        background: linear-gradient(135deg, #f0f7f8 30%, #CCECEE);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    @keyframes fadeSlideIn {
        from { opacity: 0; transform: translateY(12px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .fade-in { animation: fadeSlideIn .4s ease both; }
    @keyframes spin { to { transform: rotate(360deg); } }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-[#021829] text-[#f0f7f8]">
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Back Link --}}
    <a href="{{ route('suppliers.index') }}"
       class="inline-flex items-center gap-1.5 text-[#CCECEE]/50 hover:text-[#CCECEE] text-sm font-medium mb-8 transition-colors no-underline">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7-7l-7 7 7 7"/>
        </svg>
        Back to Suppliers
    </a>

    {{-- Page Header --}}
    <div class="mb-8">
        <p class="text-xs font-semibold tracking-[.18em] uppercase text-[#CCECEE] mb-1">
            Wellmeadows Hospital
        </p>
        <h1 class="text-3xl font-bold gradient-text leading-tight mb-2">Edit Supplier</h1>
        <div class="inline-flex items-center gap-1.5 text-sm text-[#CCECEE]/50">
            Currently editing
            <span class="inline-flex items-center gap-1 bg-[#CCECEE]/10 border border-[#CCECEE]/20 text-[#CCECEE] text-xs font-semibold px-2.5 py-0.5 rounded-full">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                </svg>
                #{{ $supplier->supplier_number }}
            </span>
        </div>
    </div>

    {{-- Validation Error Summary --}}
    @if ($errors->any())
        <div class="flex items-start gap-3 bg-red-500/10 border border-red-500/30 text-red-400 text-sm font-medium p-4 rounded-xl mb-6">
            <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <p class="font-bold mb-1">Please fix the following errors:</p>
                <ul class="list-disc pl-4 space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- Success Flash --}}
    @if (session('success'))
        <div class="flex items-center gap-3 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-sm font-medium p-4 rounded-xl mb-6">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Form Card --}}
    <div class="bg-[#032d4f] border border-[#CCECEE]/15 rounded-2xl overflow-hidden shadow-2xl fade-in">

        {{-- Card Header --}}
        <div class="flex items-center gap-3 px-6 py-5 border-b border-[#CCECEE]/10 bg-[#03416E]/30">
            <div class="w-9 h-9 rounded-xl bg-[#CCECEE]/10 border border-[#CCECEE]/20 flex items-center justify-center text-[#CCECEE] shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold tracking-widest uppercase text-[#CCECEE]/50">Editing Record</p>
                <h2 class="text-sm font-bold text-[#f0f7f8]">Supplier Information</h2>
            </div>
        </div>

        <form action="{{ route('suppliers.update', $supplier->supplier_number) }}"
              method="POST"
              id="supplierEditForm">
            @csrf
            @method('PUT')

            <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-5">

                {{-- Supplier Number (read-only) --}}
                <div class="sm:col-span-2 flex flex-col gap-1.5">
                    <div class="flex items-center gap-2">
                        <label class="text-xs font-semibold tracking-wider uppercase text-[#CCECEE]/60">
                            Supplier Number
                        </label>
                        <span class="inline-flex items-center gap-1 bg-white/5 border border-white/10 text-[#CCECEE]/40 text-xs font-semibold px-2 py-0.5 rounded">
                            <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/>
                            </svg>
                            Read-only
                        </span>
                    </div>
                    <input
                        type="text"
                        value="{{ $supplier->supplier_number }}"
                        disabled
                        class="bg-[#021829]/60 border border-[#CCECEE]/10 text-[#CCECEE]/40 text-sm rounded-xl px-4 py-2.5 w-full cursor-not-allowed"
                    >
                    <p class="text-xs text-[#CCECEE]/30">The supplier's primary key cannot be changed.</p>
                </div>

                {{-- Supplier Name --}}
                <div class="sm:col-span-2 flex flex-col gap-1.5">
                    <label for="supplier_name" class="text-xs font-semibold tracking-wider uppercase text-[#CCECEE]/60">
                        Supplier Name <span class="text-[#CCECEE]">*</span>
                    </label>
                    <input
                        type="text"
                        id="supplier_name"
                        name="supplier_name"
                        value="{{ old('supplier_name', $supplier->supplier_name) }}"
                        placeholder="e.g. MedPharm Supplies Inc."
                        autocomplete="organization"
                        class="bg-[#021829] border {{ $errors->has('supplier_name') ? 'border-red-400/60 ring-2 ring-red-400/10' : 'border-[#CCECEE]/20 focus:border-[#CCECEE]/60 focus:ring-2 focus:ring-[#CCECEE]/10' }} text-[#f0f7f8] placeholder-[#CCECEE]/30 text-sm rounded-xl px-4 py-2.5 w-full outline-none transition"
                    >
                    @error('supplier_name')
                        <p class="flex items-center gap-1 text-xs text-red-400">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Address --}}
                <div class="sm:col-span-2 flex flex-col gap-1.5">
                    <label for="address" class="text-xs font-semibold tracking-wider uppercase text-[#CCECEE]/60">
                        Address <span class="text-[#CCECEE]">*</span>
                    </label>
                    <input
                        type="text"
                        id="address"
                        name="address"
                        value="{{ old('address', $supplier->address) }}"
                        placeholder="e.g. 123 Pharma St., Cagayan de Oro City"
                        autocomplete="street-address"
                        class="bg-[#021829] border {{ $errors->has('address') ? 'border-red-400/60 ring-2 ring-red-400/10' : 'border-[#CCECEE]/20 focus:border-[#CCECEE]/60 focus:ring-2 focus:ring-[#CCECEE]/10' }} text-[#f0f7f8] placeholder-[#CCECEE]/30 text-sm rounded-xl px-4 py-2.5 w-full outline-none transition"
                    >
                    @error('address')
                        <p class="flex items-center gap-1 text-xs text-red-400">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Divider --}}
                <div class="sm:col-span-2 border-t border-[#CCECEE]/10"></div>

                {{-- Telephone --}}
                <div class="flex flex-col gap-1.5">
                    <label for="telephone" class="text-xs font-semibold tracking-wider uppercase text-[#CCECEE]/60">
                        Telephone <span class="text-[#CCECEE]">*</span>
                    </label>
                    <input
                        type="text"
                        id="telephone"
                        name="telephone"
                        value="{{ old('telephone', $supplier->telephone) }}"
                        placeholder="e.g. +63 88 123 4567"
                        autocomplete="tel"
                        class="bg-[#021829] border {{ $errors->has('telephone') ? 'border-red-400/60 ring-2 ring-red-400/10' : 'border-[#CCECEE]/20 focus:border-[#CCECEE]/60 focus:ring-2 focus:ring-[#CCECEE]/10' }} text-[#f0f7f8] placeholder-[#CCECEE]/30 text-sm rounded-xl px-4 py-2.5 w-full outline-none transition"
                    >
                    @error('telephone')
                        <p class="flex items-center gap-1 text-xs text-red-400">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Fax Number --}}
                <div class="flex flex-col gap-1.5">
                    <label for="fax_number" class="text-xs font-semibold tracking-wider uppercase text-[#CCECEE]/60">
                        Fax Number
                    </label>
                    <input
                        type="text"
                        id="fax_number"
                        name="fax_number"
                        value="{{ old('fax_number', $supplier->fax_number) }}"
                        placeholder="e.g. +63 88 765 4321"
                        class="bg-[#021829] border {{ $errors->has('fax_number') ? 'border-red-400/60 ring-2 ring-red-400/10' : 'border-[#CCECEE]/20 focus:border-[#CCECEE]/60 focus:ring-2 focus:ring-[#CCECEE]/10' }} text-[#f0f7f8] placeholder-[#CCECEE]/30 text-sm rounded-xl px-4 py-2.5 w-full outline-none transition"
                    >
                    <p class="text-xs text-[#CCECEE]/40">Optional — leave blank if not applicable.</p>
                    @error('fax_number')
                        <p class="flex items-center gap-1 text-xs text-red-400">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

            </div>

            {{-- Footer --}}
            <div class="flex items-center justify-between gap-3 px-6 py-4 border-t border-[#CCECEE]/10 bg-[#03416E]/10 flex-wrap">
                <p class="text-xs text-[#CCECEE]/40">
                    <span class="text-[#CCECEE]">*</span> Required fields
                </p>
                <div class="flex items-center gap-2 flex-wrap">
                    <a href="{{ route('suppliers.index') }}"
                       class="inline-flex items-center gap-2 bg-transparent border border-[#CCECEE]/20 text-[#CCECEE]/50 hover:border-[#CCECEE]/40 hover:text-[#CCECEE]/80 text-sm font-semibold px-4 py-2.5 rounded-xl transition no-underline">
                        Cancel
                    </a>
                    <button type="reset"
                            class="inline-flex items-center gap-2 bg-transparent border border-amber-400/20 text-amber-400/70 hover:border-amber-400/40 hover:bg-amber-400/5 hover:text-amber-400 text-sm font-semibold px-4 py-2.5 rounded-xl transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Reset
                    </button>
                    <button type="submit" id="submitBtn"
                            class="inline-flex items-center gap-2 bg-[#03416E] hover:bg-[#CCECEE] hover:text-[#021829] text-[#CCECEE] border border-[#CCECEE]/30 text-sm font-semibold px-5 py-2.5 rounded-xl transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-[#CCECEE]/10 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        Save Changes
                    </button>
                </div>
            </div>

        </form>
    </div>

</div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('supplierEditForm').addEventListener('submit', function () {
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = `
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5"
                 viewBox="0 0 24 24" style="animation:spin .7s linear infinite;">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 100 16 8 8 0 01-8-8z"/>
            </svg>
            Saving…`;
    });
</script>
@endpush