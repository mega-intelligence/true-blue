<div>
    <input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}" wire:model.lazy="value">
    @error('value') <span class="text-red-500">{{ $message }}</span> @enderror
</div>
