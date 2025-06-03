<?php
include 'config.php';
?>

<div class="filter-box">
    <div class="filter-section">
        <h3>Xem theo khoảng giá</h3>
        <div class="filter-list">
            <a href="#" data-param="gia" data-value="duoi-1-trieu">› Dưới 1 triệu</a>
            <a href="#" data-param="gia" data-value="1-2-trieu">› Từ 1 - 2 triệu</a>
            <a href="#" data-param="gia" data-value="2-3-trieu">› Từ 2 - 3 triệu</a>
            <a href="#" data-param="gia" data-value="3-5-trieu">› Từ 3 - 5 triệu</a>
            <a href="#" data-param="gia" data-value="5-7-trieu">› Từ 5 - 7 triệu</a>
            <a href="#" data-param="gia" data-value="7-10-trieu">› Từ 7 - 10 triệu</a>
            <a href="#" data-param="gia" data-value="10-15-trieu">› Từ 10 - 15 triệu</a>
            <a href="#" data-param="gia" data-value="tren-15-trieu">› Trên 15 triệu</a>
        </div>
    </div>

    <div class="filter-section">
        <h3>Xem theo diện tích</h3>
        <div class="filter-list">
            <a href="#" data-param="dientich" data-value="duoi-20">› Dưới 20 m²</a>
            <a href="#" data-param="dientich" data-value="20-30">› Từ 20 - 30 m²</a>
            <a href="#" data-param="dientich" data-value="30-50">› Từ 30 - 50 m²</a>
            <a href="#" data-param="dientich" data-value="50-70">› Từ 50 - 70 m²</a>
            <a href="#" data-param="dientich" data-value="70-90">› Từ 70 - 90 m²</a>
            <a href="#" data-param="dientich" data-value="trem-90">› Trên 90 m²</a>
        </div>
    </div>
</div>

<script>
    // Đặt script này vào cuối file filter.php hoặc file JS chính của bạn
    document.querySelectorAll('.filter-list a').forEach(function (link) {
        link.addEventListener('click', function (e) {
            e.preventDefault();

            const param = this.getAttribute('data-param');
            const value = this.getAttribute('data-value');

            const url = new URL(window.location.href);
            url.searchParams.set(param, value);

            // Reset page về 1 nếu đang phân trang
            url.searchParams.delete('page');

            window.location.href = url.toString();
        });
    });
</script>