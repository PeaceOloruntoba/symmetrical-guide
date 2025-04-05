@extends('layouts.app')

@section('body-class', '')
@section('body-style', 'min-height: 100vh; background-color: #f8f9fa;')

@section('content')
    <div class="container py-4">
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="badge bg-success">Products</span>
                        <h1 class="mt-2 mb-0">{{ $company->company_name }}</h1>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        @include('layouts.company-nav')

        <!-- Edit Product Form -->
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="mb-4">Edit Product</h4>

                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('company.products.update', $product->id) }}"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="name" class="form-label">Product name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" value="{{ old('name', $product->name) }}">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="category" class="form-label">Category</label>
                            <select class="form-select @error('category_id') is-invalid @enderror" id="category"
                                name="category_id">
                                <option value="" disabled>Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ (old('category_id', $product->category_id) == $category->id) ? 'selected' : '' }}>
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
                                id="price" name="price" value="{{ old('price', $product->price) }}">
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description"
                            name="description" rows="6">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Colors</label>
                        <div class="d-flex align-items-center">
                            <div id="selected-colors" class="d-flex flex-wrap gap-2 me-2">
                                @foreach($product->colors ?? [] as $color)
                                    <span class="badge rounded-pill" style="background-color: {{ $color }}">
                                        {{ $color }}
                                        <button type="button" class="btn-close btn-close-white ms-2"
                                            onclick="removeColor('{{ $color }}')"></button>
                                    </span>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal"
                                data-bs-target="#addColorModal">
                                <i class="fas fa-plus me-1"></i> Add Color
                            </button>
                        </div>
                        <div id="colors-container">
                            @foreach($product->colors ?? [] as $color)
                                <input type="hidden" name="colors[]" value="{{ $color }}" id="color-{{ $color }}">
                            @endforeach
                        </div>
                        @error('colors')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Current Images</label>
                        <div class="d-flex flex-wrap gap-3 mb-3">
                            @foreach($product->images as $image)
                                <div class="position-relative">
                                    <img src="{{ asset('storage/' . $image->image_path) }}" alt="Product Image"
                                        class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                                    <div class="position-absolute top-0 end-0 d-flex">
                                        <button type="button" class="btn btn-sm btn-danger"
                                            onclick="document.getElementById('delete-image-{{ $image->id }}').submit();">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    <form id="delete-image-{{ $image->id }}"
                                        action="{{ route('company.products.images.destroy', $image) }}" method="POST"
                                        class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            @endforeach
                        </div>

                        <label class="form-label">Add New Images</label>
                        <input type="file" class="form-control @error('new_images') is-invalid @enderror"
                            name="new_images[]" multiple accept="image/*" onchange="previewNewImages(this)">

                        <!-- New image previews -->
                        <div id="new-image-previews" class="d-flex flex-wrap gap-2 mt-2"></div>

                        @error('new_images')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                        @error('new_images.*')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Certificate</label>
                        <div class="input-group">
                            <button type="button" class="btn btn-outline-success"
                                onclick="document.getElementById('certificate').click()">
                                <i class="fas fa-file-upload me-2"></i> Upload Certificate
                            </button>
                            <input type="file" id="certificate" name="certificate" class="d-none" accept=".pdf,.doc,.docx"
                                onchange="updateCertificateLabel(this)">
                            <span class="input-group-text flex-grow-1" id="certificate-label">
                                @if($product->certificate)
                                    {{ $product->certificate->name }}
                                @endif
                            </span>
                        </div>
                        @if($product->certificate)
                            <div class="mt-2">
                                <a href="{{ Storage::url($product->certificate->certificate_path) }}" target="_blank"
                                    class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-file-download me-1"></i> Download Current Certificate
                                </a>
                            </div>
                        @endif
                        @error('certificate')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ $product->is_active ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('company.products.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-success">Update Product</button>
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
        @include('layouts.company-styles')
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

            function previewNewImages(input) {
                const previewContainer = document.getElementById('new-image-previews');

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