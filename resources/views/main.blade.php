<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BeerCSS Drag & Drop</title>
    <!-- Include BeerCSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/beercss@3.0.4/dist/cdn/beer.min.css">
    <script src="https://cdn.jsdelivr.net/npm/beercss@3.0.4/dist/cdn/beer.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <style>
        .container {
            display: flex;
            flex-direction: column;
            justify-content: space-around;
            align-items: stretch;
        }

        .drop-zone {
            border: 2px dashed #ccc;
            border-radius: 10px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
            min-height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            margin: 16px;
        }

        .drop-zone.active {
            border-color: var(--primary);
            background-color: rgba(var(--primary-rgb), 0.1);
        }

        #preview {
            max-width: 100%;
            border-radius: 8px;
        }

        .hidden {
            display: none;
        }
    </style>
</head>

<body class="surface">

    <div class="container">

        @if (!$file)
        <div class="drop-zone" id="dropZone">
            <div class="medium-text">Arraste e solte sua imagem aqui!</div>
            <div class="small-text mute">Ou</div>
            <label class="button primary">Selecione um arquivo
                <input type="file" id="fileInput" accept="image/*" hidden>
            </label>
        </div>

        <div class="progress linear hidden" id="progressBar">
            <div class="indeterminate"></div>
        </div>

        <div class="text-red hidden" id="errorMsg"></div>
        @else
        <img id="preview" alt="Preview" src="{{ $file }}">
        <form action="/api/image" method="POST">
            @method('DELETE')
            <button>
                <i>home</i>
                <span>Remover imagem</span>
            </button>
        </form>
        @endif
    </div>

    <script>
        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('fileInput');
        const preview = document.getElementById('preview');
        const errorMsg = document.getElementById('errorMsg');
        const progressBar = document.getElementById('progressBar');

        // Prevent default drag behaviors
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
            document.body.addEventListener(eventName, preventDefaults, false);
        });

        // Highlight drop zone when item is dragged over it
        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, unhighlight, false);
        });

        // Handle dropped files
        dropZone.addEventListener('drop', handleDrop, false);
        fileInput.addEventListener('change', handleFileSelect, false);

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        function highlight(e) {
            dropZone.classList.add('active');
        }

        function unhighlight(e) {
            dropZone.classList.remove('active');
        }

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            handleFiles(files);
        }

        function handleFileSelect(e) {
            const files = e.target.files;
            const formData = new FormData();
            formData.append('image', files[0]);
            fetch('/api/image', {method: 'POST', body: formData}).then(() => {window.location.reload()});
            handleFiles(files);
        }

        function handleFiles(files) {
            errorMsg.classList.add('hidden');
            progressBar.classList.remove('hidden');

            const file = files[0];
            if (!file.type.startsWith('image/')) {
                showError('Please upload an image file');
                return;
            }

            // Simulate upload progress
            setTimeout(() => {
                progressBar.classList.add('hidden');
                const reader = new FileReader();
                reader.onload = (e) => {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }, 1000);
        }

        function showError(message) {
            errorMsg.textContent = message;
            errorMsg.classList.remove('hidden');
            progressBar.classList.add('hidden');
        }
    </script>
</body>

</html>