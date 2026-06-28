<x-layout>
    <fieldset class="fieldset bg-base-200 rounded-box mx-auto w-full max-w-md">
        <legend class="fieldset-legend">Login</legend>

        <form method="POST" action="/login">
            @csrf

            <label class="label" for="email">Your email</label>
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

            <button type="submit" class="btn btn-primary mt-4">Login</button>
        </form>
    </fieldset>
</x-layout>
