@extends('layouts.app')

@section('body-class', '')
@section('body-style', 'min-height: 100vh; background-color: #f8f9fa;')

@section('content')
    <div class="container py-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h3 class="mb-4">JobiJob</h3>

                <h4 class="mb-4">Publish product</h4>

                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('company.products.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="name" class="form-label">Product name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" value="{{ old('name') }}">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="category" class="form-label">Category</label>
                            <select class="form-select @error('category_id') is-invalid @enderror" id="category"
                                name="category_id">
                                <option value="" selected disabled>Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="price" class="form-label">Price</label>
                            <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror"
                                id="price" name="price" value="{{ old('price') }}">
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Job description and requirements</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description"
                            name="description" rows="6">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Colors</label>
                        <div class="d-flex align-items-center">
                            <div id="selected-colors" class="d-flex flex-wrap gap-2 me-2">
                                @if(old('colors'))
                                    @foreach(old('colors') as $color)
                                        <span class="badge rounded-pill" style="background-color: {{ $color }}">
                                            {{ $color }}
                                            <button type="button" class="btn-close btn-close-white ms-2"
                                                onclick="removeColor('{{ $color }}')"></button>
                                        </span>
                                    @endforeach
                                @endif
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal"
                                data-bs-target="#addColorModal">
                                <i class="fas fa-plus"></i> Add colors
                            </button>
                        </div>
                        <div id="colors-container">
                            @if(old('colors'))
                                @foreach(old('colors') as $color)
                                    <input type="hidden" name="colors[]" value="{{ $color }}">
                                @endforeach
                            @endif
                        </div>
                        @error('colors')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Product pictures</label>
                        <div class="d-flex align-items-center">
                            <button type="button" class="btn btn-sm btn-outline-success"
                                onclick="document.getElementById('product_images').click()">
                                <i class="fas fa-plus"></i> Product pictures
                            </button>
                            <input type="file" id="product_images" name="images[]" multiple class="d-none" accept="image/*"
                                onchange="previewImages(this)">
                        </div>
                        <div id="image-previews" class="d-flex flex-wrap gap-2 mt-2">
                            <!-- Image previews will be displayed here -->
                        </div>
                        @error('images')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                        @error('images.*')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Certificate</label>
                        <div class="d-flex align-items-center">
                            <button type="button" class="btn btn-sm btn-outline-success"
                                onclick="document.getElementById('certificate').click()">
                                <i class="fas fa-plus"></i> Certificate
                            </button>
                            <input type="file" id="certificate" name="certificate" class="d-none" accept=".pdf,.doc,.docx"
                                onchange="updateCertificateLabel(this)">
                            <span id="certificate-label" class="ms-2"></span>
                        </div>
                        @error('certificate')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-success w-100">Publish</button>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('company.products.index') }}" class="btn btn-outline-secondary w-100">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Color Modal -->
    <div class="modal fade" id="addColorModal" tabindex="-1" aria-labelledby="addColorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addColorModalLabel">Add color</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="colorInput" class="form-label">Enter the color...</label>
                        <input type="text" class="form-control" id="colorInput" placeholder="Blue">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" onclick="addColor()">Add</button>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <style>
            .btn-success {
                background-color: #4CAF50;
                border-color: #4CAF50;
            }

            .btn-success:hover {
                background-color: #3e8e41;
                border-color: #3e8e41;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            function addColor() {
                const colorInput = document.getElementById('colorInput');
                const color = colorInput.value.trim();

                if (color) {
                    // Add color badge
                    const selectedColors = document.getElementById('selected-colors');
                    const colorBadge = document.createElement('span');
                    colorBadge.className = 'badge rounded-pill';
                    colorBadge.style.backgroundColor = color;
                    colorBadge.innerHTML = `${color} <button type="button" class="btn-close btn-close-white ms-2" onclick="removeColor('${color}')"></button>`;
                    selectedColors.appendChild(colorBadge);

                    // Add hidden input
                    const colorsContainer = document.getElementById('colors-container');
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'colors[]';
                    input.value = color;
                    input.id = `color-${color}`;
                    colorsContainer.appendChild(input);

                    // Clear input and close modal
                    colorInput.value = '';
                    const modal = bootstrap.Modal.getInstance(document.getElementById('addColorModal'));
                    modal.hide();
                }
            }

            function removeColor(color) {
                // Remove the badge
                const badge = event.target.closest('.badge');
                badge.remove();

                // Remove the hidden input
                const input = document.getElementById(`color-${color}`);
                if (input) {
                    input.remove();
                }
            }

            function previewImages(input) {
                const previewContainer = document.getElementById('image-previews');
                previewContainer.innerHTML = '';

                if (input.files) {
                    for (let i = 0; i < input.files.length; i++) {
                        const reader = new FileReader();

                        reader.onload = function (e) {
                            const preview = document.createElement('div');
                            preview.className = 'position-relative';
                            preview.innerHTML = `
                                <img src="${e.target.result}" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                                <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0" 
                                        onclick="this.parentElement.remove()">
                                    <i class="fas fa-times"></i>
                                </button>
                            `;
                            previewContainer.appendChild(preview);
                        }

                        reader.readAsDataURL(input.files[i]);
                    }
                }
            }

            function updateCertificateLabel(input) {
                const label = document.getElementById('certificate-label');
                if (input.files && input.files[0]) {
                    label.textContent = input.files[0].name;
                } else {
                    label.textContent = '';
                }
            }
        </script>
    @endpush
@endsection