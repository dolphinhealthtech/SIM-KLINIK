
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Resep Obat</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; line-height: 1.6; }
        .resep-line { border-bottom: 1px solid #ccc; padding: 5px 0; }
        .title { text-align: center; font-size: 18px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="title">Resep Obat</div>
    @foreach ($resepList as $line)
        <div class="resep-line">
            {!! nl2br(e($line)) !!}
        </div>
    @endforeach
</body>
</html>
