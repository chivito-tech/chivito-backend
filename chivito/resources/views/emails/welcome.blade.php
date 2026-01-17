<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <title>Welcome to Brega</title>
  </head>
  <body style="font-family: Arial, sans-serif; color: #111827;">
    <h2>Welcome, {{ $name }}!</h2>
    <p>Thanks for joining Brega. Click the button below to access your account.</p>
    <p>
      <a
        href="{{ $link }}"
        style="display:inline-block;background:#7e22ce;color:#ffffff;text-decoration:none;padding:10px 16px;border-radius:8px;"
      >
        Open Brega
      </a>
    </p>
    <p style="color:#6b7280;font-size:12px;">
      If the button doesn't work, copy and paste this link into your browser:
      <br />
      {{ $link }}
    </p>
  </body>
</html>
