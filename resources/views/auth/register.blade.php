<x-layout>
    <fieldset class="fieldset bg-base-200 border-base-300 rounded-box mx-auto w-full max-w-md border p-4">
        <legend class="fieldset-legend">Register</legend>

        <form method="POST" action="/register">
            @csrf

            <label class="label" for="name">Your name</label>
            <input
                id="name"
                name="name"
                type="text"
                class="input w-full @error('name') input-error @enderror"
                value="{{ old('name') }}"
                required
            />
            <x-forms.error name="name" />

            <label class="label mt-2" for="email">Your email</label>
            <input
                id="email"
                name="email"
                type="email"
                class="input w-full @error('email') input-error @enderror"
                value="{{ old('email') }}"
                required
            />
            <x-forms.error name="email" />

            <label class="label mt-2" for="password">Password</label>
            <input
                id="password"
                name="password"
                type="password"
                class="input w-full @error('password') input-error @enderror"
                required
            />
            <x-forms.error name="password" />

            <button type="submit" class="btn btn-primary mt-4">Register</button>
        </form>
    </fieldset>
</x-layout>
