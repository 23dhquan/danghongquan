@extends('welcome')
@section('title', 'Danh Sách Nhà')

@section('content')
    <div class="container-fluid content-inner mt-n5 py-0">
        <div class="row">
            @foreach ($houses as $house)
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card {{ $house->is_rented == 0 ? 'bg-danger' : 'bg-success' }}
">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="bg-white rounded p-3">
                                    <svg class="icon-20" xmlns="http://www.w3.org/2000/svg" width="20px" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="text-end">
                                    <h4 class="card-title text-white">{{ $house->name }}</h4>
                                    <p class=" text-white">{{ $house->description }}</p>
                                    <p class="text-white"><strong>Giá: </strong>{{ number_format($house->price, 0, ',', '.') }} VNĐ</p>
                                    <p class="text-white"><strong>Khu vực: </strong>{{ $house->area_name }}</p>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault(); // Ngăn chặn gửi form ngay lập tức

                const form = this; // Lưu trữ form để sử dụng sau

                Swal.fire({
                    title: 'Bạn có chắc chắn không?',
                    text: "Thao tác này không thể hoàn tác!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Xóa',
                    width: '32rem',
                    padding: '1rem',
                    scrollbarPadding: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: form.action,
                            method: form.method,
                            data: $(form).serialize(),
                            success: function(response) {
                                Swal.fire({
                                    title: 'Đã xóa!',
                                    text: 'Nhà đã được xóa thành công.',
                                    icon: 'success',
                                    scrollbarPadding: false
                                });
                                $(form).closest('.col-lg-3').remove(); // Xóa thẻ hiện tại
                            },
                            error: function(response) {
                                Swal.fire(
                                    'Lỗi!',
                                    'Không thể xóa nhà.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
