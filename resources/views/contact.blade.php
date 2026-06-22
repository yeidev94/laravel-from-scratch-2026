<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
</head>
<body>

    <header>
        <h1>Contact Us</h1>
        <p>We'd love to hear from you. Reach out with any questions or feedback.</p>
    </header>

    <main>

        <section>
            <h2>Contact Information</h2>

            <h3>Address</h3>
            <p>
                123 Business Avenue<br>
                Suite 500<br>
                New York, NY 10001
            </p>

            <h3>Phone</h3>
            <p>+1 (555) 123-4567</p>

            <h3>Email</h3>
            <p>contact@examplecompany.com</p>

            <h3>Business Hours</h3>

        </section>

        <hr>

        <section>
            <h2>Send Us a Message</h2>

            <form action="#" method="post">

                <p>
                    <label for="name">Full Name</label><br>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        required
                    >
                </p>

                <p>
                    <label for="email">Email Address</label><br>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        required
                    >
                </p>

                <p>
                    <label for="subject">Subject</label><br>
                    <input
                        type="text"
                        id="subject"
                        name="subject"
                    >
                </p>

                <p>
                    <label for="message">Message</label><br>
                    <textarea
                        id="message"
                        name="message"
                        rows="6"
                        required
                    ></textarea>
                </p>

                <button type="submit">
                    Send Message
                </button>

            </form>
        </section>

        <hr>

    </main>

    <footer>
        <hr>
        <p>
            © 2026 Example Company. All rights reserved.
        </p>
    </footer>

</body>
</html>