<nav class="border-b border-border">
    <div class="max-w-7xl mx-auto h-16 px-6 flex items-center justify-between">
        <a href="/" aria-label="Idea logo">
            <img src="/images/logo.png" alt="Idea logo" width="100">
        </a>

        <div class="flex items-center gap-5">
            @guest
                <a href="/login">Sign In</a>
                <a href="/register" class="btn">Register</a>
            @endguest

            @auth
                <form action="/logout" method="POST">
                    @csrf
                    <button type="submit">Log Out</button>
                </form>
            @endauth
        </div>
    </div>
</nav>
