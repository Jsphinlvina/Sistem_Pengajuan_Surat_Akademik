<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

<style>
    @page {
        margin: 140px 50px 80px 50px; /* atas kanan bawah kiri */
    }

    body{
        font-family: "Times New Roman", serif;
        font-size: 12pt;
    }

    table{
        border-collapse: collapse;
    }

    th, td{
        border:1px solid black;
        padding:10px;
    }

    header{
        position: fixed;
        top: -120px;
        left: 0;
        right: 0;
        height: 100px;
    }

    img{
        width: 100%; position: relative; top: -35px; }

    .kop-img{
        width: 100%;
    }

</style>

</head>

<body>

<header>
    @if($kop_surat_path && file_exists($kop_surat_path))
        <img src="{{ $kop_surat_path }}" class="kop-img">
    @endif
</header>

<main>
    {!! $content !!}
</main>

</body>
</html>
