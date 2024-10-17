@extends('welcome')
@section('title', 'Chỉnh Sửa Phòng Thuê')

@section('content')
    <div class="container-fluid content-inner mt-n5 py-0">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Chỉnh Sửa Phòng Thuê</h4>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('house.update', $houses->house_id) }}" method="POST">
                            @csrf
                            @method('PUT') <!-- Cần khai báo PUT để gửi yêu cầu cập nhật -->

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="mb-3">
                                <label for="name" class="form-label">Mã Số Phòng</label>
                                <input type="text" class="form-control" id="name" name="name"
                                       value="{{ old('name', $houses->name) }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="price" class="form-label">Giá Tiền</label>
                                <input type="text" class="form-control" id="price" name="price"
                                       value="{{ old('price', $houses->price) }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Mô Tả</label>
                                <input type="text" class="form-control" id="description" name="description"
                                       value="{{ old('description', $houses->description) }}" required>
                            </div>



                            <div class="mb-3">
                                <label for="area_id" class="form-label">Chọn Khu Vực</label>
                                <select class="form-control" id="area_id" name="area_id">
                                    @foreach($areas as $area)
                                        <option value="{{ $area->area_id }}"
                                            {{ old('area_id', $houses->area_id) == $area->area_id ? 'selected' : '' }}>
                                            {{ $area->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>


                            <button type="submit" class="btn btn-primary">Cập Nhật</button>
                            <a href="{{ route('house.list') }}" class="btn btn-secondary">Quay lại</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
