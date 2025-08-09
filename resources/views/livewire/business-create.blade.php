<div class="max-w-md mx-auto p-6 bg-white rounded shadow">
    @if (session()->has('message'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="submit" class="space-y-4" enctype="multipart/form-data">
        <div>
            <label for="name" class="block font-medium text-gray-700">Business Name <span
                    class="text-red-500">*</span></label>
            <input type="text" id="name" wire:model.defer="name" class="w-full border rounded px-3 py-2" required>
            @error('name')
                <span class="text-red-600 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="address" class="block font-medium text-gray-700">Address</label>
            <textarea id="address" wire:model.defer="address" class="w-full border rounded px-3 py-2" rows="2"></textarea>
            @error('address')
                <span class="text-red-600 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="phone" class="block font-medium text-gray-700">Phone</label>
            <input type="text" id="phone" wire:model.defer="phone" class="w-full border rounded px-3 py-2">
            @error('phone')
                <span class="text-red-600 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="phone" class="block font-medium text-gray-700">Logo</label>
            <input type="file" id="phone" wire:model.defer="logo" class="w-full border rounded px-3 py-2">
            @error('logo')
                <span class="text-red-600 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="email" class="block font-medium text-gray-700">Email</label>
            <input type="email" id="email" wire:model.defer="email" class="w-full border rounded px-3 py-2">
            @error('email')
                <span class="text-red-600 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Save Business
            </button>
        </div>
    </form>
</div>
