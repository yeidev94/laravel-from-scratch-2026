<x-layout title="Register">
    <x-form
        title="Register an account"
        description="Start tracking your ideas today."
    >
        <form method="POST" action="/register" class="space-y-6">
            @csrf

            <div class="space-y-4">
                <x-form.field name="name" label="What is your name?" />
                <x-form.field name="email" label="Email" type="email" />
                <x-form.field name="password" label="Password" type="password" />
            </div>

            <button type="submit" class="btn mt-2 h-10" data-test="register-button">Create Account</button>
        </form>
    </x-form>
</x-layout>
