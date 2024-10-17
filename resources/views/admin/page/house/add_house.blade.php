@extends('welcome')
@section('title', 'Thêm Phòng Thuê')

@section('content')
    <div class="container-fluid content-inner mt-n5 py-0">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Thêm Phòng Thuê Mới</h4>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('house.store') }}" method="POST"> <!-- Đường dẫn đến route lưu khu vực -->
                            @csrf <!-- Thêm token bảo mật -->
                            <div class="mb-3">
                                <label for="name" class="form-label">Mã Số Phòng</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="price" class="form-label">Giá Tiền</label>
                                <input type="text" class="form-control" id="price" name="price" required>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Mô Tả</label>
                                <input type="text" class="form-control" id="description" name="description" required>
                            </div>
                            <div class="mb-3">
                                <label for="area_id" class="form-label">Chọn Khu Vực</label>
                                <select class="form-control" id="area_id" name="area_id" >
                                    @foreach($areas as $area)
                                        <option  value="{{ $area->area_id }}">{{ $area->name }}</option> <!-- Hiển thị ID và tên của khu vực -->
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Thêm</button>
                            <a href="{{ route('area.list') }}" class="btn btn-secondary">Quay lại</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
