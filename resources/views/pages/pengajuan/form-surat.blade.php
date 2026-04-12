<form action="{{ route('pengajuan.store') }}" method="POST">

@csrf

<input type="hidden"
name="template_surat_id"
value="{{ $template->id }}">

@foreach($fields as $field)

<div class="mb-3">

<label>
{{ ucwords(str_replace('_',' ',$field)) }}
</label>

<input
type="text"
name="fields[{{ $field }}]"
class="form-control"
required>

</div>

@endforeach

<button type="submit">
Kirim Pengajuan
</button>

</form>
