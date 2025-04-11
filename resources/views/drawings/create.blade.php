<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Drawing') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('drawings.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <x-input-label for="title" :value="__('Title (Optional)')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <label for="drawing-canvas" class="block font-medium text-sm text-gray-700">
                                {{ __('Drawing Canvas') }}
                            </label>
                            <div class="mt-1 border border-gray-300 rounded-md p-2 bg-gray-50">
                                <div id="drawing-container" class="relative w-full" style="height: 400px;">
                                    <canvas id="drawing-canvas" class="border border-gray-300 bg-white w-full h-full"></canvas>
                                </div>
                                <div class="flex items-center mt-2 space-x-2">
                                    <label for="brush-size">Brush Size:</label>
                                    <input type="range" id="brush-size" min="1" max="20" value="5" class="w-32">

                                    <label for="brush-color" class="ml-4">Color:</label>
                                    <input type="color" id="brush-color" value="#000000" class="w-12 h-8">

                                    <button type="button" id="clear-canvas" class="ml-4 px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">
                                        Clear
                                    </button>
                                </div>
                            </div>
                            <textarea id="image_data" name="image_data" hidden></textarea>
                            <x-input-error :messages="$errors->get('image_data')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('drawings.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
                                {{ __('Cancel') }}
                            </a>
                            <x-primary-button id="submit-drawing">
                                {{ __('Post Drawing') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const canvas = document.getElementById('drawing-canvas');
            const ctx = canvas.getContext('2d');
            const brushSize = document.getElementById('brush-size');
            const brushColor = document.getElementById('brush-color');
            const clearButton = document.getElementById('clear-canvas');
            const imageDataInput = document.getElementById('image_data');
            const submitButton = document.getElementById('submit-drawing');

            function resizeCanvas() {
                const container = document.getElementById('drawing-container');
                canvas.width = container.clientWidth;
                canvas.height = container.clientHeight;
                ctx.fillStyle = 'white';
                ctx.fillRect(0, 0, canvas.width, canvas.height);
            }

            resizeCanvas();
            window.addEventListener('resize', resizeCanvas);

            let drawing = false;

            function startDrawing(e) {
                drawing = true;
                draw(e);
            }

            function stopDrawing() {
                drawing = false;
                ctx.beginPath();
            }

            function draw(e) {
                if (!drawing) return;

                ctx.lineWidth = brushSize.value;
                ctx.lineCap = 'round';
                ctx.strokeStyle = brushColor.value;

                const rect = canvas.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;

                ctx.lineTo(x, y);
                ctx.stroke();
                ctx.beginPath();
                ctx.moveTo(x, y);
            }

            function handleTouch(e) {
                e.preventDefault();
                const touch = e.touches[0];
                const mouseEvent = new MouseEvent('mousemove', {
                    clientX: touch.clientX,
                    clientY: touch.clientY
                });
                draw(mouseEvent);
            }

            canvas.addEventListener('mousedown', startDrawing);
            canvas.addEventListener('mouseup', stopDrawing);
            canvas.addEventListener('mousemove', draw);
            canvas.addEventListener('mouseout', stopDrawing);

            canvas.addEventListener('touchstart', (e) => {
                e.preventDefault();
                startDrawing(e.touches[0]);
            });
            canvas.addEventListener('touchend', stopDrawing);
            canvas.addEventListener('touchmove', handleTouch);

            clearButton.addEventListener('click', function() {
                ctx.fillStyle = 'white';
                ctx.fillRect(0, 0, canvas.width, canvas.height);
            });

            submitButton.addEventListener('click', function() {
                const svg = convertCanvasToSVG(canvas);
                imageDataInput.value = svg;
            });

            function convertCanvasToSVG(canvas) {
                const width = canvas.width;
                const height = canvas.height;
                const imageData = canvas.toDataURL('image/png');

                return `<svg width="${width}" height="${height}" xmlns="http://www.w3.org/2000/svg">
                    <image href="${imageData}" width="${width}" height="${height}" />
                </svg>`;
            }
        });
    </script>
</x-app-layout>
