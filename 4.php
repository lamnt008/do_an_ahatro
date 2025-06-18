<script>
    $(document).ready(function () {
        // Xóa toàn bộ event listener cũ
        $(document).off('click', '.save-post-btn');

        // Thêm event listener mới
        $(document).on('click', '.save-post-btn', function (e) {
            e.preventDefault();
            e.stopImmediatePropagation();

            var button = $(this);
            var postId = button.data('post-id');
            var isSaved = button.hasClass('saved');

            // Hiển thị loading
            button.prop('disabled', true).html('<span class="spinner"></span>');

            $.ajax({
                url: 'save_post.php',
                type: 'POST',
                data: {
                    post_id: postId,
                    action: isSaved ? 'unsave' : 'save'
                },
                dataType: 'json',
                statusCode: {
                    200: function (response) {
                        // Xử lý khi HTTP status là 200
                        if (response.success) {
                            button.toggleClass('saved');
                            button.text(response.saved ? 'Đã lưu' : 'Lưu bài');
                        } else {
                            alert(response.message || 'Thao tác không thành công');
                        }
                    }
                },
                success: function (response, status, xhr) {
                    // Đã xử lý trong statusCode 200
                },
                error: function (xhr, status, error) {
                    // Chỉ xử lý lỗi kết nối hoặc lỗi server thực sự
                    if (xhr.status > 200) {
                        console.error('AJAX Error:', status, error);
                        alert('Lỗi kết nối đến server');
                    }
                },
                complete: function () {
                    button.prop('disabled', false);
                }
            });
        });
    });
</script>