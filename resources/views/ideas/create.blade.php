<x-layout >
    <form method="POST" action="/ideas">
        @csrf
    <div class="col-span-full">
            <label for="description" class="block text-sm/6 font-medium text-white">Create New Idea</label>
            <div class="mt-2">
                <textarea id="description" name="description" class="textarea w-full @error('description') textarea-error @enderror">{{ old('description') }}</textarea>
            </div>
            <p class="mt-3 text-sm/6 text-gray-400">Have an idea you want to save for later?</p>
            <x-forms.error name='description'/>
        </div>
    <div class="mt-6 flex items-center  gap-x-6">
        <button type="submit" class="btn">
            Save
        </button>
    </div>
    </form>
</x-layout>