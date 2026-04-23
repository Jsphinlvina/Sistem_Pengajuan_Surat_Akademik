<table border="1" cellpadding="6" cellspacing="0"">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>NRP</th>
        </tr>
    </thead>
    <tbody>
        @foreach($mahasiswa as $i => $m)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $m->nama }}</td>
                <td>{{ $m->nrp }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
