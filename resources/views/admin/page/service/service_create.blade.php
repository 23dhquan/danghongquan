@extends('welcome')
@section('title', 'Thêm Dịch Vụ')

@section('content')
    <div class="container-fluid content-inner mt-n5 py-0">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Thêm Dịch Vụ Mới</h4>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('service.store') }}" method="POST"> <!-- Đường dẫn đến route lưu Dịch Vụ -->
                            @csrf <!-- Thêm token bảo mật -->
                            <div class="mb-3">
                                <label for="name" class="form-label">Tên Dịch Vụ</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Giá Tiền</label>
                                <input type="text" class="form-control" id="price" name="price" required>
                            </div>

                            <button type="submit" class="btn btn-primary">Thêm</button>
                            <a href="{{ route('service.list') }}" class="btn btn-secondary">Quay lại</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
