<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

<style>
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
</style>

</head>

<body>

@if($kop_surat_path && file_exists($kop_surat_path))
    <img src="{{ $kop_surat_path }}" style="width:100%;">
@endif

<br><br>

{!! $content !!}

</body>
</html>
