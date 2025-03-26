document.addEventListener('DOMContentLoaded', function() {
    // Kiểm tra và khởi tạo các biến cơ bản
    const canvas = document.getElementById('saleChart');
    if (!canvas) {
        console.error("Không tìm thấy canvas với ID 'saleChart'");
        return;
    }

    let initialSale;
    try {
        initialSale = JSON.parse(canvas.dataset.sale || '[]');
    } catch (e) {
        console.error("Lỗi khi parse dữ liệu sale:", e);
        initialSale = [];
    }

    let daysInMonth = parseInt(canvas.dataset.days) || 31;
    if (isNaN(daysInMonth) || daysInMonth < 1) {
        console.warn("daysInMonth không hợp lệ, sử dụng giá trị mặc định 31");
        daysInMonth = 31;
    }

    const ctx = canvas.getContext('2d');
    let saleChart;

    // Hàm tính tổng doanh thu hàng ngày
    function calculateDailyTotals(salesData) {
        if (!Array.isArray(salesData)) {
            console.error("Lỗi: salesData không phải là một mảng!");
            return Array(daysInMonth).fill(0);
        }
        
        const dailyTotals = Array(daysInMonth).fill(0);
        salesData.forEach(element => {
            const day = parseInt(element?.day);
            const price = parseFloat(element?.total_price || 0);
            if (day >= 1 && day <= daysInMonth && !isNaN(price)) {
                dailyTotals[day - 1] += price;
            }
        });
        return dailyTotals;
    }

    // Hàm tạo biểu đồ
    function createChart(labels, data) {
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(0, 123, 255, 0.5)');
        gradient.addColorStop(1, 'rgba(0, 123, 255, 0.03)');

        const xLabelStep = Math.ceil(daysInMonth / 5);

        saleChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    borderColor: '#007bff',
                    borderWidth: 1,
                    pointRadius: 2,
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#007bff',
                    fill: true,
                    backgroundColor: gradient,
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { 
                        beginAtZero: true, 
                        grid: { color: '#eaeaea' } 
                    },
                    x: {
                        grid: { display: false },
                        ticks: {
                            callback: function(value, index) {
                                return (index + 1) % xLabelStep === 1 ? labels[index] : '';
                            }
                        }
                    }
                }
            }
        });
    }

    // Hàm cập nhật biểu đồ
    function updateChart() {
        const monthElement = document.getElementById('month');
        if (!monthElement) {
            console.error("Không tìm thấy phần tử month");
            return;
        }
        
        const month = monthElement.value;
        if (!month) {
            console.error("Giá trị tháng không hợp lệ");
            return;
        }

        // Thêm loading state nếu cần
        fetch(`/admin/dashboard/sale?month=${month}?year=${year}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(salesData => {
                console.log(salesData)
                if (saleChart) {
                    saleChart.destroy();
                }
                const dailyTotals = calculateDailyTotals(salesData.data);
                const labels = Array.from({ length: daysInMonth }, (_, i) => i + 1);
                createChart(labels, dailyTotals);
            })
            .catch(error => {
                console.error("Lỗi khi lấy dữ liệu:", error);
                // Có thể thêm thông báo cho người dùng ở đây
            });
    }

    // Khởi tạo biểu đồ
    if (typeof Chart === 'undefined') {
        console.error("Thư viện Chart.js chưa được load");
        return;
    }

    const initialLabels = Array.from({ length: daysInMonth }, (_, i) => i + 1);
    const initialDailyTotals = calculateDailyTotals(initialSale);
    createChart(initialLabels, initialDailyTotals);

    // Thêm event listener cho month
    const monthElement = document.getElementById('month');
    if (monthElement) {
        monthElement.addEventListener('change', updateChart);
    } else {
        console.warn("Không tìm thấy phần tử month để thêm event listener");
    }
});