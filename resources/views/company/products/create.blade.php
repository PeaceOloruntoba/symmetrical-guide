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

        <!-- Create Product Form -->
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="mb-4">发布产品</h4>

                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('company.products.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="name" class="form-label">产品名称</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" value="{{ old('name') }}">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="category" class="form-label">类别</label>
                            <select class="form-select @error('category_id') is-invalid @enderror" id="category"
                                name="category_id">
                                <option value="" selected disabled>类别</option>
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
                            <label for="price" class="form-label">价格</label>
                            <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror"
                                id="price" name="price" value="{{ old('price') }}">
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">工作描述和要求</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description"
                            name="description" rows="6">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">颜色</label>
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
                                <i class="fas fa-plus"></i> 添加颜色
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
                        <label class="form-label">产品图片</label>
                        <div class="d-flex align-items-center">
                            <button type="button" class="btn btn-sm btn-outline-success"
                                onclick="document.getElementById('product_images').click()">
                                <i class="fas fa-plus"></i> 产品图片
                            </button>
                            <input type="file" id="product_images" name="product_images[]" multiple class="d-none" accept="image/*"
                                onchange="previewImages(this)">
                        </div>
                        <div id="image-previews" class="d-flex flex-wrap gap-2 mt-2">
                            <!-- Image previews will be displayed here -->
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
                            <button type="button" class="btn btn-outline-success"
                                onclick="document.getElementById('certificate').click()">
                                <i class="fas fa-file-upload me-2"></i> 上传证书
                            </button>
                            <input type="file" id="certificate" name="certificate" class="d-none" accept=".pdf,.doc,.docx"
                                onchange="updateCertificateLabel(this)">
                            <span class="input-group-text flex-grow-1" id="certificate-label"></span>
                        </div>
                        @error('certificate')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('company.products.index') }}" class="btn btn-outline-secondary">取消</a>
                        <button type="submit" class="btn btn-success">发布产品</button>
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