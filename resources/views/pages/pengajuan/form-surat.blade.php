<form action="{{ route('pengajuan.store') }}" method="POST">

@csrf
<input type="hidden" name="template_surat_id" value="{{ $template->id }}">

<h3 class="mb-4 font-bold">
{{ $template->nama }}
</h3>


{{-- FIELD SISTEM (READONLY) --}}
@foreach($systemFields as $key => $value)

<x-form-input
name="system[{{ $key }}]"
label="{{ ucwords(str_replace('_',' ',$key)) }}"
:value="$value"
readonly
/>

@endforeach

@foreach($dynamicFields as $field)

@continue(array_key_exists($field,$systemFields))

<x-form-input
name="fields[{{ $field }}]"
label="{{ ucwords(str_replace('_',' ',$field)) }}"
/>

@endforeach


<button class="btn btn-success mt-4">
Ajukan Surat
</button>

</form>
