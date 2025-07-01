<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/style.css">

</head>

<body>

    <div class="banner">
        <h4 style="font-weight: bold;">Đăng tin dễ dàng - Tìm trọ nhanh chóng!</h4>
        <?php
        include 'config.php';
        $where = [];
        $params = [];

        if (!empty($_GET['tinh'])) {
            $where[] = "TinhThanh = ?";
            $params[] = $_GET['tinh'];
        }

        if (!empty($_GET['quan'])) {
            $where[] = "QuanHuyen = ?";
            $params[] = $_GET['quan'];
        }

        if (!empty($_GET['id'])) {
            $where[] = "idLoaiPhong = ?";
            $params[] = $_GET['id'];
        }

        if (!empty($_GET['gia'])) {
            switch ($_GET['gia']) {
                case 'duoi-1-trieu':
                    $where[] = "GiaChoThue < ?";
                    $params[] = 1000000;
                    break;
                case '1-2-trieu':
                    $where[] = "GiaChoThue >= ? AND GiaChoThue <= ?";
                    array_push($params, 1000000, 2000000);
                    break;
                case '2-3-trieu':
                    $where[] = "GiaChoThue >= ? AND GiaChoThue <= ?";
                    array_push($params, 2000000, 3000000);
                    break;
                case '3-5-trieu':
                    $where[] = "GiaChoThue >= ? AND GiaChoThue <= ?";
                    array_push($params, 3000000, 5000000);
                    break;
                case '5-7-trieu':
                    $where[] = "GiaChoThue >= ? AND GiaChoThue <= ?";
                    array_push($params, 5000000, 7000000);
                    break;
                case '7-10-trieu':
                    $where[] = "GiaChoThue >= ? AND GiaChoThue <= ?";
                    array_push($params, 7000000, 10000000);
                    break;
                case '10-15-trieu':
                    $where[] = "GiaChoThue >= ? AND GiaChoThue <= ?";
                    array_push($params, 10000000, 15000000);
                    break;
                case 'tren-15-trieu':
                    $where[] = "GiaChoThue > ?";
                    $params[] = 15000000;
                    break;
            }
        }

        if (!empty($_GET['dientich'])) {
            switch ($_GET['dientich']) {
                case 'duoi-20':
                    $where[] = "DienTich < ?";
                    $params[] = 20;
                    break;
                case '20-30':
                    $where[] = "DienTich >= ? AND DienTich <= ?";
                    array_push($params, 20, 30);
                    break;
                case '30-50':
                    $where[] = "DienTich >= ? AND DienTich <= ?";
                    array_push($params, 30, 50);
                    break;
                case '50-70':
                    $where[] = "DienTich >= ? AND DienTich <= ?";
                    array_push($params, 50, 70);
                    break;
                case '70-90':
                    $where[] = "DienTich >= ? AND DienTich <= ?";
                    array_push($params, 70, 90);
                    break;
                case 'tren-90':
                    $where[] = "DienTich > ?";
                    $params[] = 90;
                    break;
            }
        }

        $sql = "SELECT COUNT(*) as total FROM phong_tro";
        if (!empty($where)) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }

        $stmt = mysqli_prepare($conn, $sql);

        if (!empty($params)) {
            $types = '';
            foreach ($params as $param) {
                $types .= is_int($param) ? 'i' : 's';
            }
            mysqli_stmt_bind_param($stmt, $types, ...$params);
        }

        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $total_rows = mysqli_fetch_assoc($result)['total'];
        echo "Có $total_rows tin đăng cho thuê";
        ?>

        <h5 style="font-weight: bold;">Chọn khu vực:</h5>
        <div class="tabs" id="city-tabs"></div>
        <div class="districts" id="district-list"></div>
    </div>

    <script>
        let tinhData = {};
        let quanData = {};
        const tabUuTien = ["79", "01", "48", "74"];

        Promise.all([
            fetch('tinh_tp.json').then(res => res.json()),
            fetch('quan_huyen.json').then(res => res.json())
        ]).then(([tinhJson, quanJson]) => {
            tinhData = tinhJson;
            quanData = quanJson;

            const cityTabs = document.getElementById('city-tabs');

            tabUuTien.forEach(maTinh => {
                const tab = document.createElement('div');
                tab.className = 'tab';
                tab.textContent = tinhData[maTinh].name;


                tab.onclick = () => {
                    const selectedTinh = tinhData[maTinh].name;
                    const currentParams = new URLSearchParams(window.location.search);

                    currentParams.set('tinh', selectedTinh);
                    currentParams.delete('quan');
                    currentParams.delete('page');

                    window.location.href = window.location.pathname + '?' + currentParams.toString();
                };

                cityTabs.appendChild(tab);
            });


            const khacTab = document.createElement('div');
            khacTab.className = 'tab';
            khacTab.textContent = 'Khác ▼';

            const dropdown = document.createElement('div');
            dropdown.className = 'dropdown';

            for (const [maTinh, tinh] of Object.entries(tinhData)) {
                if (!tabUuTien.includes(maTinh)) {
                    const option = document.createElement('div');
                    option.textContent = tinh.name;


                    option.onclick = (e) => {
                        e.stopPropagation();
                        removeActiveTabs();
                        khacTab.classList.add('active');
                        khacTab.textContent = tinh.name + ' ▼';

                        const currentParams = new URLSearchParams(window.location.search);
                        currentParams.set('tinh', tinh.name);
                        currentParams.delete('quan');
                        currentParams.delete('page');

                        window.location.href = window.location.pathname + '?' + currentParams.toString();

                        showDistricts(maTinh);
                    };


                    dropdown.appendChild(option);
                }
            }

            khacTab.appendChild(dropdown);
            cityTabs.appendChild(khacTab);

            const urlParams = new URLSearchParams(window.location.search);
            const selectedTinhFromURL = urlParams.get('tinh');
            const selectedQuanFromURL = urlParams.get('quan');

            if (selectedTinhFromURL) {

                const maTinhChon = Object.keys(tinhData).find(ma => tinhData[ma].name === selectedTinhFromURL);

                if (maTinhChon) {

                    const tabs = document.querySelectorAll('.tab');
                    tabs.forEach(tab => {
                        if (tab.textContent.includes(selectedTinhFromURL)) {
                            tab.classList.add('active');
                        }
                    });

                    showDistricts(maTinhChon);
                }
            }

        });


        function showDistricts(maTinh) {
            const list = document.getElementById('district-list');
            list.innerHTML = '';

            for (const [maQuan, quan] of Object.entries(quanData)) {
                if (quan.parent_code === maTinh) {
                    const div = document.createElement('div');
                    div.className = 'district';
                    div.textContent = quan.name;



                    div.onclick = () => {
                        const selectedTinh = tinhData[maTinh].name;
                        const selectedQuan = quan.name;

                        const currentUrl = new URL(window.location.href);
                        const params = new URLSearchParams(currentUrl.search);


                        params.set('tinh', selectedTinh);
                        params.set('quan', selectedQuan);



                        const currentPage = window.location.pathname;
                        window.location.href = `${currentPage}?${params.toString()}`;
                    };

                    list.appendChild(div);
                }
            }
        }

        function removeActiveTabs() {
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
        }
    </script>

</body>

</html>