<div class="max-w-4xl mx-auto p-6 bg-white rounded-lg shadow-md ring-1 ring-gray-200">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Your Businesses</h2>

    @if ($businesses->isEmpty())
        <p class="text-gray-600 text-center py-10">You have no businesses yet.</p>
    @else
        <table class="w-full table-auto border-collapse border border-gray-200">
            <thead>
                <tr class="bg-gray-50 text-left text-gray-700 uppercase text-sm font-medium">
                    <th class="border border-gray-300 px-4 py-2">Logo</th>
                    <th class="border border-gray-300 px-4 py-2">Name</th>
                    <th class="border border-gray-300 px-4 py-2">Phone</th>
                    <th class="border border-gray-300 px-4 py-2">Email</th>
                    <th class="border border-gray-300 px-4 py-2">Address</th>
                    <th class="border border-gray-300 px-4 py-2">Edit</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($businesses as $business)
                    <tr class="hover:bg-gray-100">
                        <td class="border border-gray-300 px-0 py-0 w-24 h-24"> <!-- fixed width and height -->
                            @if ($business->logo_path)
                                <img src="{{ asset('storage/' . $business->logo_path) }}"
                                    alt="{{ $business->name }} Logo" class="w-full h-full object-cover rounded">
                            @else
                                <span class="text-gray-400 italic text-sm block p-4 text-center">No logo</span>
                            @endif
                        </td>
                        <td class="border border-gray-300 px-4 py-2 font-semibold text-gray-900">{{ $business->name }}
                        </td>
                        <td class="border border-gray-300 px-4 py-2">{{ $business->phone }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $business->email }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $business->address }}</td>
                        <td class="border border-gray-300 px-4 py-2">
                            <button wire:click="edit({{ $business->id }})">Edit</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    {{-- Edit Modal --}}

    <div x-data="{ open: @entangle('showEditModal') }" x-show="open"
        class="fixed inset-0 backdrop-blur-md flex items-center justify-center z-50"
        style="background-color: rgba(255,255,255,0.1); display: none;" @keydown.escape.window="open = false">


        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6" @click.away="open = false" x-transition>
            <h3 class="text-xl font-semibold mb-4">Edit Business</h3>
            <form wire:submit.prevent="update">
                <div class="space-y-4">
                    <div>
                        <label for="name" class="block font-medium text-gray-700">Name <span
                                class="text-red-500">*</span></label>
                        <input type="text" id="name" wire:model.defer="editingBusiness.name"
                            class="w-full border rounded px-3 py-2" required>
                        @error('editingBusiness.name')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="address" class="block font-medium text-gray-700">Address</label>
                        <textarea id="address" wire:model.defer="editingBusiness.address" class="w-full border rounded px-3 py-2"
                            rows="2"></textarea>
                        @error('editingBusiness.address')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block font-medium text-gray-700">Phone</label>
                        <input type="text" id="phone" wire:model.defer="editingBusiness.phone"
                            class="w-full border rounded px-3 py-2">
                        @error('editingBusiness.phone')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block font-medium text-gray-700">Email</label>
                        <input type="email" id="email" wire:model.defer="editingBusiness.email"
                            class="w-full border rounded px-3 py-2">
                        @error('editingBusiness.email')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- You can add logo upload here if desired --}}

                    <div class="flex justify-end space-x-2">
                        <button type="button" @click="open = false"
                            class="px-4 py-2 rounded border border-gray-300 hover:bg-gray-100">Cancel</button>
                        <button type="submit"
                            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save</button>
                    </div>
                </div>
            </form>

        </div>

    </div>

</div>
