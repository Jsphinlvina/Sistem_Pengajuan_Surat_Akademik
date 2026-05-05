@extends('starter')

@section('content')

<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/3">
    <div class="px-5 py-4 sm:px-6 sm:py-5">
        <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
            Preview & Atur Posisi QR
        </h3>
    </div>

    <div class="space-y-6 border-t border-gray-100 p-5 sm:p-6 dark:border-gray-800 px-10">
        <div id="pdf-container" style="position: relative;">
            <canvas id="pdf-canvas"></canvas>

            <div id="qr-wrapper" class="bg-white p-2 pt-1" style="position:absolute; top:100px; left:100px; cursor:move; text-align:center;">

                <div style="font-size:12px; font-weight:bold;">U.B.</div>
                <img id="qr"
                     src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=preview"
                     style="width:80px; display:block;">
            </div>
        </div>
    </div>
</div>

<div class="mt-3 rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/3">
    <form id="formLhsm" method="POST" action="{{ route('pengajuan.accept.lhsm', $pengajuan->id) }}">
        @csrf
        <div class="flex justify-end items-center p-5">
            <input type="hidden" name="x" id="posX">
            <input type="hidden" name="y" id="posY">

            <input type="hidden" name="canvas_width" id="canvasW">
            <input type="hidden" name="canvas_height" id="canvasH">

            <button type="submit" class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg">
                Setujui & Simpan
            </button>
        </div>
    </form>
</div>

<script src="{{('https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js')}}"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const url = "{{ $file }}";

    const pdfjsLib = window['pdfjs-dist/build/pdf'];
    pdfjsLib.GlobalWorkerOptions.workerSrc =
        'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

    const canvas = document.getElementById('pdf-canvas');
    const ctx = canvas.getContext('2d');

    pdfjsLib.getDocument(url).promise.then(pdf => {
        pdf.getPage(1).then(page => {
            const viewport = page.getViewport({ scale: 1.5 });

            canvas.height = viewport.height;
            canvas.width = viewport.width;

            page.render({
                canvasContext: ctx,
                viewport: viewport
            });
        });
    });

    const qr = document.getElementById('qr-wrapper');
    const container = document.getElementById('pdf-container');

    qr.onmousedown = function(e) {
        e.preventDefault();

        const containerRect = container.getBoundingClientRect();
        const qrRect = qr.getBoundingClientRect();

        let shiftX = e.clientX - qrRect.left;
        let shiftY = e.clientY - qrRect.top;

        function moveAt(clientX, clientY) {
            let newLeft = clientX - containerRect.left - shiftX;
            let newTop  = clientY - containerRect.top - shiftY;

            newLeft = Math.max(0, Math.min(newLeft, container.offsetWidth - qr.offsetWidth));
            newTop  = Math.max(0, Math.min(newTop, container.offsetHeight - qr.offsetHeight));

            qr.style.left = newLeft + 'px';
            qr.style.top  = newTop + 'px';
        }

        function onMouseMove(e) {
            moveAt(e.clientX, e.clientY);
        }

        document.addEventListener('mousemove', onMouseMove);

        document.addEventListener('mouseup', function() {
            document.removeEventListener('mousemove', onMouseMove);
        }, { once: true });
    };

    const form = document.getElementById('formLhsm');
    if (form) {
        form.onsubmit = function(e) {
            const qr = document.getElementById('qr-wrapper');
            const canvas = document.getElementById('pdf-canvas');

            const centerX = qr.offsetLeft + (qr.offsetWidth / 2);
            const centerY = qr.offsetTop + (qr.offsetHeight / 2);

            document.getElementById('posX').value = centerX;
            document.getElementById('posY').value = centerY;

            document.getElementById('canvasW').value = canvas.offsetWidth;
            document.getElementById('canvasH').value = canvas.offsetHeight;

            return true;
        };
    }
});
</script>

@endsection
