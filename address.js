let tinhData = {};
let quanData = {};

// Hàm để điền dữ liệu cho dropdown tỉnh/thành
function populateTinhs() {
    const tinhSelect = document.getElementById('tinh');
    tinhSelect.innerHTML = '<option value="">Chọn tỉnh/thành</option>'; // Thêm option mặc định

    for (const [maTinh, tinh] of Object.entries(tinhData)) {
        const option = document.createElement('option');
        option.value = maTinh;
        option.textContent = tinh.name;
        option.setAttribute('data-ten', tinh.name);
        tinhSelect.appendChild(option);
    }
}

// Hàm để điền dữ liệu cho dropdown quận/huyện dựa trên mã tỉnh
// Tham số selectedQuanName để chọn quận/huyện ban đầu (nếu có)
function populateQuans(selectedMaTinh, selectedQuanName = '') {
    const quanSelect = document.getElementById('quan');
    quanSelect.innerHTML = '<option value="">Chọn quận/huyện</option>'; // Thêm option mặc định

    if (selectedMaTinh) { // Chỉ điền nếu có mã tỉnh được chọn
        for (const [maQuan, quan] of Object.entries(quanData)) {
            if (quan.parent_code === selectedMaTinh) {
                const option = document.createElement('option');
                option.value = quan.name;
                option.textContent = quan.name;
                quanSelect.appendChild(option);
            }
        }
    }

    // Chọn quận/huyện ban đầu nếu có và khớp
    if (selectedQuanName) {
        quanSelect.value = selectedQuanName;
    }
}


Promise.all([
    fetch('tinh_tp.json').then(res => res.json()),
    fetch('quan_huyen.json').then(res => res.json())
])
    .then(([tinhJson, quanJson]) => {
        tinhData = tinhJson;
        quanData = quanJson;

        const tinhSelect = document.getElementById('tinh');
        const quanSelect = document.getElementById('quan');
        const tenTinhHidden = document.getElementById('tenTinhHidden');

        // 1. Điền dữ liệu cho dropdown tỉnh/thành
        populateTinhs();

        // 2. Xử lý chọn tỉnh/thành ban đầu và điền quận/huyện tương ứng
        // Phần này chỉ chạy nếu initialTinhName có giá trị (tức là ở trang sửa tin)
        if (initialTinhName) {
            let initialMaTinh = '';
            // Tìm mã tỉnh từ tên tỉnh ban đầu
            for (const [maTinh, tinh] of Object.entries(tinhData)) {
                if (tinh.name === initialTinhName) {
                    initialMaTinh = maTinh;
                    break;
                }
            }

            if (initialMaTinh) {
                tinhSelect.value = initialMaTinh; // Chọn tỉnh/thành ban đầu
                tenTinhHidden.value = initialTinhName; // Cập nhật input hidden
                populateQuans(initialMaTinh, initialQuanName); // Điền và chọn quận/huyện ban đầu
            }
        }

        // 3. Thêm sự kiện 'change' cho dropdown tỉnh/thành
        tinhSelect.addEventListener('change', function () {
            const selectedMaTinh = this.value;
            const selectedOption = this.options[this.selectedIndex];
            const tenTinh = selectedOption ? selectedOption.getAttribute('data-ten') : ''; // Đảm bảo selectedOption tồn tại
            tenTinhHidden.value = tenTinh;

            // Điền lại quận/huyện khi tỉnh thay đổi (hoặc được chọn lại)
            populateQuans(selectedMaTinh);
        });

        // Thêm sự kiện 'change' cho dropdown quận/huyện để cập nhật input hidden
        quanSelect.addEventListener('change', function () {
            const selectedQuanName = this.value;
            // Nếu bạn muốn lưu tên quận/huyện vào một input hidden khác, bạn có thể thêm ở đây
            // Ví dụ: document.getElementById('tenQuanHidden').value = selectedQuanName;
        });

    })
    .catch(error => {
        console.error("Lỗi khi tải dữ liệu địa chỉ:", error);
        alert("Không thể tải dữ liệu địa chỉ. Vui lòng thử lại sau.");
    });