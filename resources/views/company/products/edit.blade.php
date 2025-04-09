@extends('layouts.app')

@section('body-class', '')
@section('body-style', 'min-height: 100vh; background-color: #f8f9fa;')

@section('content')
    <div class="container py-4">
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="badge bg-success">产品</span>
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
                <h4 class="mb-4">编辑产品</h4>

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
                            <label for="name" class="form-label">产品名称</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" value="{{ old('name', $product->name) }}">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="category" class="form-label">类别</label>
                            <select class="form-select @error('category_id') is-invalid @enderror" id="category"
                                name="category_id">
                                <option value="" disabled>类别</option>
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
                            <label for="price" class="form-label">价格</label>
                            <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror"
                                id="price" name="price" value="{{ old('price', $product->price) }}">
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Package Dimensions -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="width" class="form-label">宽度 (cm)</label>
                            <input type="number" step="0.01" class="form-control @error('width') is-invalid @enderror"
                                id="width" name="width" value="{{ old('width', $product->width) }}">
                            @error('width')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="height" class="form-label">高度 (cm)</label>
                            <input type="number" step="0.01" class="form-control @error('height') is-invalid @enderror"
                                id="height" name="height" value="{{ old('height', $product->height) }}">
                            @error('height')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="length" class="form-label">长度 (cm)</label>
                            <input type="number" step="0.01" class="form-control @error('length') is-invalid @enderror"
                                id="length" name="length" value="{{ old('length', $product->length) }}">
                            @error('length')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="weight" class="form-label">重量 (kg)</label>
                            <input type="number" step="0.01" class="form-control @error('weight') is-invalid @enderror"
                                id="weight" name="weight" value="{{ old('weight', $product->weight) }}">
                            @error('weight')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">描述</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description"
                            name="description" rows="6">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">颜色</label>
                        <div class="d-flex align-items-center">
                            <div id="selected-colors" class="d-flex flex-wrap gap-2 me-2">
                                @php
                                    // Get colors from either the relationship or the attribute
                                    $productColors = $product->colors()->count() > 0
                                        ? $product->colors()->get()->pluck('color')->toArray()
                                        : (is_array($product->colors) ? $product->colors : []);
                                @endphp

                                @foreach($productColors as $color)
                                    <span class="badge rounded-pill bg-success text-white">
                                        {{ $color }}
                                        <button type="button" class="btn-close btn-close-white ms-2"
                                            onclick="removeColor('{{ str_replace(['#', ' '], '_', $color) }}')"></button>
                                    </span>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal"
                                data-bs-target="#addColorModal">
                                <i class="fas fa-plus me-1"></i> 添加颜色
                            </button>
                        </div>
                        <div id="colors-container">
                            @foreach($productColors as $color)
                                <input type="hidden" name="colors[]" value="{{ $color }}"
                                    id="color-{{ str_replace(['#', ' '], '_', $color) }}">
                            @endforeach
                        </div>
                        @error('colors')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">当前图片</label>
                        <div class="d-flex flex-wrap gap-3 mb-3">
                            @if($product->images && count($product->images) > 0)
                                @foreach($product->images as $image)
                                    <div class="position-relative">
                                        <img src="{{ asset('storage/' . $image->image_path) }}" class="img-thumbnail"
                                            style="width: 100px; height: 100px; object-fit: cover;">
                                        <div class="position-absolute top-0 end-0">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="delete_images[]"
                                                    value="{{ $image->id }}" id="delete-image-{{ $image->id }}">
                                                <label class="form-check-label" for="delete-image-{{ $image->id }}">
                                                    删除
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">添加新图片</label>
                        <input type="file" class="form-control" id="product_images" name="product_images[]" multiple
                            accept="image/*" onchange="previewNewImages(this)">
                        <div id="new-image-previews" class="d-flex flex-wrap gap-2 mt-2">
                            <!-- New image previews will be displayed here -->
                        </div>
                        @error('product_images')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                        @error('product_images.*')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">证书</label>
                        <div class="input-group">
                            <input type="file" class="form-control" id="certificate" name="certificate"
                                onchange="updateCertificateLabel(this)">
                            <label class="input-group-text" for="certificate" id="certificate-label">
                                @if($product->certificates && $product->certificates->count() > 0)
                                    {{ $product->certificates->first()->name }}
                                @endif
                            </label>
                        </div>
                        @error('certificate')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ $product->is_active ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">激活</label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('company.products.index') }}" class="btn btn-outline-secondary">取消</a>
                        <button type="submit" class="btn btn-success">更新产品</button>
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
                    <h5 class="modal-title" id="addColorModalLabel">添加颜色</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="colorInput" class="form-label">输入颜色...</label>
                        <input type="text" class="form-control" id="colorInput" placeholder="蓝色">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">取消</button>
                    <button type="button" class="btn btn-success" onclick="addColor()">添加</button>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        @include('layouts.company-styles')
    @endpush

    @push('scripts')
        <script>
            // Add this at the beginning of your script section to debug
            console.log('Product colors:', @json($product->colors));

            function addColor() {
                const colorInput = document.getElementById('colorInput');
                const color = colorInput.value.trim();

                if (color) {
                    // Check if this color already exists
                    const existingInput = document.querySelector(`input[value="${color}"]`);
                    if (existingInput) {
                        alert('This color is already added');
                        return;
                    }

                    // Create a safe ID by replacing spaces and special characters
                    const safeColorId = color.replace(/[^a-zA-Z0-9]/g, '_');

                    // Add color badge
                    const selectedColors = document.getElementById('selected-colors');
                    const colorBadge = document.createElement('span');
                    colorBadge.className = 'badge rounded-pill bg-success text-white';
                    colorBadge.innerHTML = `${color} <button type="button" class="btn-close btn-close-white ms-2" onclick="removeColor('${safeColorId}')"></button>`;
                    selectedColors.appendChild(colorBadge);

                    // Add hidden input
                    const colorsContainer = document.getElementById('colors-container');
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'colors[]';
                    input.value = color;
                    input.id = `color-${safeColorId}`;
                    colorsContainer.appendChild(input);

                    // Clear input and close modal
                    colorInput.value = '';
                    const modal = bootstrap.Modal.getInstance(document.getElementById('addColorModal'));
                    modal.hide();
                }
            }

            function removeColor(safeColorId) {
                // Remove the badge
                const badge = event.target.closest('.badge');
                if (badge) {
                    badge.remove();
                }

                // Remove the hidden input
                const input = document.getElementById(`color-${safeColorId}`);
                if (input) {
                    input.remove();
                }
            }

            // Initialize existing colors with safe IDs
            document.addEventListener('DOMContentLoaded', function () {
                const colorBadges = document.querySelectorAll('#selected-colors .badge');
                colorBadges.forEach(badge => {
                    const colorText = badge.textContent.trim();
                    const originalColor = colorText.replace(/\s+.*$/, ''); // Get text before any whitespace
                    const safeColorId = originalColor.replace(/[^a-zA-Z0-9]/g, '_');

                    // Update the onclick handler with the safe ID
                    const closeButton = badge.querySelector('.btn-close');
                    if (closeButton) {
                        closeButton.setAttribute('onclick', `removeColor('${safeColorId}')`);
                    }
                });
            });

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