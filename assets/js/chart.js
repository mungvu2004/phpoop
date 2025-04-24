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
        
        // Log dữ liệu đầu vào để kiểm tra
        
        // Nếu salesData đã là một mảng số (từ response.data), trả về trực tiếp
        if (salesData.length > 0 && typeof salesData[0] === 'number') {
           
            return salesData;
        }
        
        // Nếu là mảng object từ initialSale, xử lý như trước
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
        gradient.addColorStop(0, 'rgba(74, 217, 145, 0.5)');
        gradient.addColorStop(1, 'rgba(74, 217, 145, 0.05)');

        const xLabelStep = Math.ceil(daysInMonth / 10);

        saleChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    borderColor: '#4AD991',
                    borderWidth: 2,
                    pointRadius: 3,
                    pointHoverRadius: 8,
                    pointBackgroundColor: '#4AD991',
                    pointHoverBackgroundColor: '#fff',
                    pointBorderColor: '#fff',
                    pointHoverBorderColor: '#4AD991',
                    pointBorderWidth: 2,
                    fill: true,
                    backgroundColor: gradient,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.7)',
                        padding: 10,
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        },
                        displayColors: false,
                        callbacks: {
                            title: function(context) {
                                return 'Day ' + context[0].label;
                            },
                            label: function(context) {
                                return 'Revenue: ' + context.formattedValue + ' VND';
                            }
                        }
                    }
                },
                scales: {
                    y: { 
                        beginAtZero: true, 
                        grid: { color: '#eaeaea' },
                        ticks: {
                            font: {
                                size: 12
                            },
                            color: '#666'
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: {
                                size: 12
                            },
                            color: '#666',
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

        // Lấy năm hiện tại
        const year = new Date().getFullYear();
        
        // Hiển thị loading state
        if (saleChart) {
            saleChart.destroy();
        }
        
        const loadingDiv = document.createElement('div');
        loadingDiv.id = 'chart-loading';
        loadingDiv.style.cssText = 'position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: #666; font-size: 14px;';
        loadingDiv.innerText = 'Đang tải dữ liệu...';
        
        canvas.parentNode.style.position = 'relative';
        canvas.parentNode.appendChild(loadingDiv);
        
        // Lấy đường dẫn tuyệt đối
        const baseUrl = window.location.origin;
        const url = `${baseUrl}/admin/dashboard/sale?month=${month}&year=${year}`;
        
        console.log("Đang tải dữ liệu từ:", url);
        
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Mã lỗi: ${response.status}`);
                }
                return response.json();
            })
            .then(salesData => {
                console.log("Dữ liệu nhận được:", JSON.stringify(salesData, null, 2));
                
                // Xóa loading message
                const loadingElement = document.getElementById('chart-loading');
                if (loadingElement) {
                    loadingElement.remove();
                }
                
                if (!salesData) {
                    throw new Error('Dữ liệu không hợp lệ');
                }
                
                // Lấy số ngày trong tháng từ API response
                if (salesData.labels && Array.isArray(salesData.labels)) {
                    daysInMonth = salesData.labels.length;
                } else {
                    // Tính số ngày từ tháng và năm nếu API không trả về
                    daysInMonth = new Date(year, month, 0).getDate();
                }
                
                let chartData;
                let chartLabels;
                
                // Kiểm tra cấu trúc dữ liệu trả về
                if (salesData.data && Array.isArray(salesData.data)) {
                    // API đã trả về đúng cấu trúc dữ liệu theo ngày
                    chartData = salesData.data;
                    chartLabels = salesData.labels;
                    console.log("Sử dụng dữ liệu định dạng chuẩn:", {chartLabels, chartData});
                } else {
                    // Tự tạo dữ liệu từ response gốc
                    chartLabels = Array.from({ length: daysInMonth }, (_, i) => i + 1);
                    chartData = Array(daysInMonth).fill(0);
                    
                    // Đảm bảo salesData có thể lặp qua
                    const dataArray = Array.isArray(salesData) ? salesData : [];
                    
                    // Điền dữ liệu vào mảng kết quả
                    dataArray.forEach(item => {
                        if (item && item.day) {
                            const dayIndex = parseInt(item.day) - 1;
                            if (dayIndex >= 0 && dayIndex < daysInMonth) {
                                chartData[dayIndex] = parseFloat(item.total_price || 0);
                            }
                        }
                    });
                    console.log("Đã xử lý dữ liệu thô:", {chartLabels, chartData});
                }
                
                // Tạo biểu đồ với dữ liệu đã xử lý
                createChart(chartLabels, chartData);
            })
            .catch(error => {
                console.error("Lỗi khi lấy dữ liệu:", error);
                
                // Xóa loading message
                const loadingElement = document.getElementById('chart-loading');
                if (loadingElement) {
                    loadingElement.innerText = 'Không thể tải dữ liệu. Vui lòng thử lại sau.';
                    loadingElement.style.color = '#dc3545';
                }
                
                // Tạo biểu đồ rỗng nếu có lỗi
                const labels = Array.from({ length: daysInMonth }, (_, i) => i + 1);
                createChart(labels, Array(daysInMonth).fill(0));
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