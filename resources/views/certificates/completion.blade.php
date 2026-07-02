<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Certificate — {{ $user->name }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; margin: 0; padding: 40px; color: #1e293b; }
        .frame { border: 8px solid #4f46e5; padding: 36px; text-align: center; min-height: 420px; }
        .brand { font-size: 14px; letter-spacing: 3px; color: #64748b; text-transform: uppercase; }
        h1 { font-size: 34px; margin: 18px 0 8px; color: #312e81; }
        h2 { font-size: 22px; margin: 0 0 24px; color: #0f172a; }
        .name { font-size: 28px; font-weight: bold; color: #4f46e5; margin: 16px 0; }
        .text { font-size: 15px; line-height: 1.7; max-width: 680px; margin: 0 auto 24px; }
        .meta { font-size: 12px; color: #64748b; margin-top: 30px; }
        .seal { display: inline-block; margin-top: 20px; padding: 10px 24px; border: 2px solid #059669; color: #059669; border-radius: 999px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="frame">
        <p class="brand">Arivexa Career Guidance</p>
        <h1>Certificate of Completion</h1>
        <p class="text">This certifies that</p>
        <p class="name">{{ $user->name }}</p>
        <p class="text">
            has successfully completed the full learning roadmap
            @if ($career)
                for <strong>{{ $career->name }}</strong>
            @endif
            on the Arivexa platform, demonstrating commitment to career development and structured learning.
        </p>
        @if ($roadmap)
            <p class="text"><strong>Roadmap:</strong> {{ $roadmap->title }} — 100% completed</p>
        @endif
        <p class="seal">Verified Achievement</p>
        <p class="meta">Issued on {{ $issuedAt->timezone('Asia/Colombo')->format('d F Y') }} · Sri Lanka</p>
    </div>
</body>
</html>
