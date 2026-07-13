<x-layout title="Log In">
    <x-form
        title="Log in"
        description="Glad to have you back."
    >
        <form method="POST" action="/login" class="space-y-6">
            @csrf

            <div class="space-y-4">
                <x-form.field name="email" label="Email" type="email" />
                <x-form.field name="password" label="Password" type="password" />
            </div>

            <button type="submit" class="btn mt-2 h-10" data-test="login-button">Sign In</button>
        </form>
    </x-form>
</x-layout>
