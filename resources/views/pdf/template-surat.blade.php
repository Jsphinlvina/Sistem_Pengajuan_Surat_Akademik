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

<img src="{{ public_path('image/kop_surat.jpeg') }}" style="width:100%;">

<br><br>

{!! $content !!}

</body>
</html>
