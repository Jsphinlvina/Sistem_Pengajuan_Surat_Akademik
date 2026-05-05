<table border="1" cellpadding="6" cellspacing="0" style="margin: auto;" >
    <thead>
        <tr>
            <th>No</th>
            <th>NRP</th>
            <th>Nama</th>
        </tr>
    </thead>
    <tbody>
        @foreach($mahasiswa as $i => $m)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $m['nrp'] }}</td>
                <td>{{ $m['nama'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
