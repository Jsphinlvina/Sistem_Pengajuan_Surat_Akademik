@php
$templateSurat = $templateSurat ?? null;
$mode = $mode ?? 'create';
$isEdit = $mode === 'edit';
@endphp

<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white mb-6">
    <div class="justify-between flex items-center px-5 py-4 sm:px-6 sm:py-5">
        <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
            {{ $mode === 'create' ? 'Tambah Template Surat' : ($mode === 'edit' ? 'Edit Template Surat' : 'Detail Template Surat') }}
        </h3>
        @if($isEdit)
            <a class="inline-flex items-center gap-2 px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-gray-400 shadow-theme-xs hover:bg-gray-600"
                    href="{{ route('template-surat.preview', $templateSurat->id) }}">
                    Preview Template
            </a>
        @endif
    </div>
     <div class="text-sm font-medium text-gray-700 space-y-6 border-t border-gray-100 p-5 sm:p-6 dark:border-gray-800 px-10">
         <p >Keterangan dynamic field</p>
         <div class="grid grid-cols-3">
             <div class="flex gap-4">
                 <div>
                     <p>Kode Surat</p>
                     <p>Nama Kepala Program Studi</p>
                     <p>NIK Kepala Program Studi</p>
                     <p></p>
                     <p>Nama Mahasiswa</p>
                     <p>NRP Mahasiswa</p>
                     <p>Tanggal Lulus</p>
                 </div>
                 <div>
                     <p>:</p>
                     <p>:</p>
                     <p>:</p>
                     <p></p>
                     <p>:</p>
                     <p>:</p>
                     <p>:</p>
                 </div>
                 <div>
                     <p>kode_surat</p>
                     <p>nama_kaprodi</p>
                     <p>nik_kaprodi</p>
                     <p></p>
                     <p>nama_mahasiswa</p>
                     <p>nrp_mahasiswa</p>
                     <p>tanggal_lulus</p>
                 </div>
             </div>
             <div class="flex gap-4">
                 <div>
                     <p>Alamat Mahasiswa</p>
                     <p>Periode Semester</p>
                     <p>Keperluan Surat</p>
                     <p></p>
                     <p>Nama Mata Kuliah</p>
                     <p>Kode Mata Kuliah</p>
                     <p>Nama yang Dituju</p>
                 </div>
                 <div>
                     <p>:</p>
                     <p>:</p>
                     <p>:</p>
                     <p></p>
                     <p>:</p>
                     <p>:</p>
                     <p>:</p>
                 </div>
                 <div>
                     <p>alamat_mahasiswa</p>
                     <p>periode_semester</p>
                     <p>keperluan_surat</p>
                     <p></p>
                     <p>nama_mata_kuliah</p>
                     <p>kode_mata_kuliah</p>
                     <p>nama_dituju</p>
                 </div>
             </div>
             <div class="flex gap-4">
                 <div>
                     <p>Jabatan yang dituju</p>
                     <p>Topik Tugas</p>
                 </div>
                 <div>
                     <p>:</p>
                     <p>:</p>
                 </div>
                 <div>
                     <p>jabatan_dituju</p>
                     <p>topik_tugas</p>
                 </div>
             </div>
         </div>
     </div>
</div>
<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white">
    <div class="space-y-6 border-t border-gray-100 p-5 sm:p-6 dark:border-gray-800 px-10">

        <form action="{{
                $mode === 'create'
                    ? route('template-surat.store')
                    : ($mode === 'edit' ? route('template-surat.update', $templateSurat) : '#')
            }}" method="POST">
            @csrf
            @if($isEdit)
            @method('PUT')
            @endif

            <div class="grid grid-cols-2 gap-6 mb-6">
                <x-form-input name="nama" label="Nama Template Surat" :value="$templateSurat?->nama" placeholder="Surat Keterangan Aktif" />
                <x-form-input name="kode" label="Kode Template Surat" :value="$templateSurat?->kode" :readonly="$isEdit"
                    placeholder="SKA"/>
            </div>

            <div class="grid mb-6">
                <div>
                      <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Deskripsi</label>
                      <textarea name="deskripsi" placeholder="Catatan untuk template." type="text" rows="6"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                      >{{ $templateSurat?->deskripsi }}</textarea>
                    </div>
            </div>

            <div class="grid mb-6">
                <div>
                      <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Template Surat</label>
                      <textarea name="xml_content" placeholder="Isi Template Surat" type="text" rows="6" id="editor"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                      >{{ $templateSurat?->xml_content }}</textarea>
                </div>
            </div>
            <div class="mt-10">
                @if($mode === 'create')
                <button type="submit"
                    class="px-4 py-3 text-sm font-medium text-white rounded-lg bg-emerald-500 hover:bg-emerald-600">
                    Tambahkan Template Surat
                </button>
                @elseif($mode === 'edit')
                <button type="submit"
                    class="px-4 py-3 text-sm font-medium text-white rounded-lg bg-yellow-500 hover:bg-yellow-600">
                    Update Template Surat
                </button>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Place the first <script> tag in your HTML's <head> -->
<script src="{{ asset('https://cdn.tiny.cloud/1/h7kk3imczcm3ndiv45v8j7gwzex8xxpwr1rf2vko7hi4fpgn/tinymce/8/tinymce.min.js')}}" referrerpolicy="origin" crossorigin="anonymous"></script>

<!-- Place the following <script> and <textarea> tags your HTML's <body> -->
<script>
  tinymce.init({
    selector: '#editor',
    plugins: [
      'anchor', 'autolink', 'charmap', 'code', 'codesample', 'emoticons', 'link', 'lists', 'media', 'searchreplace', 'table', 'visualblocks', 'wordcount',
      // Try the most popular premium features until Apr 19, 2026:
      'checklist', 'mediaembed', 'casechange', 'formatpainter', 'pageembed', 'a11ychecker', 'tinymcespellchecker', 'permanentpen', 'powerpaste', 'advtable', 'advcode', 'advtemplate', 'tinymceai', 'uploadcare', 'mentions', 'tinycomments', 'tableofcontents', 'footnotes', 'mergetags', 'autocorrect', 'typography', 'inlinecss', 'markdown','importword', 'exportword', 'exportpdf'
    ],
    toolbar: 'undo redo | tinymceai-chat tinymceai-quickactions tinymceai-review | blocks fontfamily fontsize | bold italic underline strikethrough | link media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography uploadcare | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat | code',
    tinycomments_mode: 'embedded',
    tinycomments_author: 'Author name',
    mergetags_list: [
      { value: 'First.Name', title: 'First Name' },
      { value: 'Email', title: 'Email' },
    ],
    tinymceai_token_provider: async () => {
      await fetch(`https://demo.api.tiny.cloud/1/h7kk3imczcm3ndiv45v8j7gwzex8xxpwr1rf2vko7hi4fpgn/auth/random`, { method: "POST", credentials: "include" });
      return { token: await fetch(`https://demo.api.tiny.cloud/1/h7kk3imczcm3ndiv45v8j7gwzex8xxpwr1rf2vko7hi4fpgn/jwt/tinymceai`, { credentials: "include" }).then(r => r.text()) };
    },
    uploadcare_public_key: '4e6aef27e9fb0d675181',
  });
</script>
