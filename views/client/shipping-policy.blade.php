@extends('client.layouts.main')

@push('styles')
    <link rel="stylesheet" href="{{ file_url('assets/client/shipping-policy.css') }}">
@endpush

@section('content')
    <div class="shipping-policy-container">
        <h1 class="policy-title">Chính sách giao nhận, vận chuyển, kiểm hàng</h1>
        
        <div class="policy-section">
            <h2 class="section-title">1. Phạm vi giao hàng:</h2>
            <p class="section-content">
                Hiện nay, chúng tôi chỉ có thể chuyển hàng đến các địa chỉ trong phạm vi đất nước Việt Nam. Thời gian của đơn hàng được tính từ lúc chúng tôi hoàn tất việc xác nhận
                đơn hàng với bạn đến khi nhận được hàng, không kể các ngày lễ, Thứ 7 và Chủ Nhật. Trong đó, thời gian giao hàng tính từ lúc đơn hàng được chúng tôi chuyển giao cho
                đơn vị vận chuyển.
            </p>
            <p class="section-note">
                Lưu ý: Thời gian giao hàng có thể kéo dài hơn dự kiến do ảnh hưởng bởi tình hình thiên tai, dịch bệnh, hoặc các tình huống bất khả kháng khác...
            </p>
        </div>
        
        <div class="policy-section">
            <h2 class="section-title">2. Phí giao hàng:</h2>
            <p class="section-content">
                Tùy vào địa điểm nhận hàng của bạn mà phí giao hàng được ước tính như sau:
            </p>
            
            <div class="shipping-table-container">
                <table class="shipping-table">
                    <thead>
                        <tr>
                            <th>Khu vực</th>
                            <th>Phí giao hàng (VNĐ)</th>
                            <th>Thời gian giao hàng</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td rowspan="2" class="region">Hồ Chí Minh</td>
                            <td><span class="location">Nội thành</span> <span class="price">20,000</span></td>
                            <td rowspan="2" class="time">Từ 03-05 ngày</td>
                        </tr>
                        <tr>
                            <td><span class="location">Ngoại Thành</span> <span class="price">20,000</span></td>
                        </tr>
                        
                        <tr>
                            <td rowspan="2" class="region">Các tỉnh phía Nam<br>(từ Bình Định tới mũi Cà Mau)</td>
                            <td><span class="location">Nội thành</span> <span class="price">20,000</span></td>
                            <td rowspan="2" class="time">Từ 03-05 ngày</td>
                        </tr>
                        <tr>
                            <td><span class="location">Ngoại Thành</span> <span class="price">20,000</span></td>
                        </tr>
                        
                        <tr>
                            <td rowspan="2" class="region">Các tỉnh miền Trung</td>
                            <td><span class="location">Nội thành</span> <span class="price">20,000</span></td>
                            <td rowspan="2" class="time">Từ 03-04 ngày</td>
                        </tr>
                        <tr>
                            <td><span class="location">Ngoại Thành</span> <span class="price">20,000</span></td>
                        </tr>
                        
                        <tr>
                            <td rowspan="2" class="region">Hà Nội</td>
                            <td><span class="location">Nội thành</span> <span class="price">20,000</span></td>
                            <td rowspan="2" class="time">Từ 01-03 ngày</td>
                        </tr>
                        <tr>
                            <td><span class="location">Ngoại Thành</span> <span class="price">20,000</span></td>
                        </tr>
                        
                        <tr>
                            <td rowspan="2" class="region">Các tỉnh còn lại</td>
                            <td><span class="location">Nội thành</span> <span class="price">20,000</span></td>
                            <td rowspan="2" class="time">Từ 03-04 ngày</td>
                        </tr>
                        <tr>
                            <td><span class="location">Ngoại Thành</span> <span class="price">20,000</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div class="shipping-notes">
                <p>- Chính sách miễn phí giao hàng sẽ được áp dụng theo chương trình ưu đãi của từng thời điểm và được thông báo tại website.</p>
                <p>- Phí giao hàng (nếu có) sẽ được tính vào giá trị đơn hàng khi khách hàng thanh toán.</p>
            </div>
        </div>
        
        <div class="policy-section">
            <h2 class="section-title">3. Xem hàng và Đồng kiểm:</h2>
            <p class="section-content">
                Các đơn hàng tại https://feelab.vn/ sẽ được áp dụng chính sách "Mở hộp và đồng kiểm trước khi nhận hàng". Theo đó, khách hàng được khuyến khích mở kiện hàng 
                kiểm tra để kiểm ngoại quan và sản phẩm (*) trước khi thanh toán tiền và xác nhận việc giao hàng.
            </p>
            <p class="section-content">
                Đối với các đơn hàng đã đồng kiểm và phát hiện lỗi nhưng không khiếu nại với nhân viên giao hàng, chúng tôi rất tiếc vì không thể hỗ trợ hoàn trả.
            </p>
            <p class="section-content">
                (*) Bạn chỉ được kiểm tra về mặt ngoại quan của sản phẩm, xem sản phẩm có đúng như bạn đã đặt hàng không? Số lượng, màu sắc có giống với mô tả không? Sản phẩm 
                có bị bể vỡ, móp méo trong quá trình vận chuyển không? Việc kiểm tra sẽ không bao gồm mở seal thử nghiệm (nếu có), dùng sản phẩm (mở hương đến tận nắp niêm 
                phong, bơm thử sản phẩm, ...) hay kiểm tra sâu (ưu điểm thử...).
            </p>
            <p class="section-content">
                Trong trường hợp khách hàng không trực tiếp đồng kiểm khi nhận hàng, chúng tôi khuyến khích khách hàng quay lại kiện hàng khi nhận từ nhân viên giao hàng và quay 
                video quá trình mở kiện hàng để làm bằng chứng nếu có trình trạng về sau.
            </p>
            <p class="section-content">
                Khách vui lòng lưu lại chứng nhận hàng bị phản hiện kiểm hàng có dấu hiệu rủi, móp méo, không còn nguyên vẹn hoặc sai thông tin người nhận.
            </p>
            <p class="section-content">
                Nếu quý khách không hài lòng hoặc có vấn đề với sản phẩm nhận được, quý khách có thể khiếu nại đến chúng tôi thông qua website https://feelab.vn/ hoặc Email: 
                feelab@gmail.com, Điện thoại: 0865359083 trong vòng 24 giờ kể từ khi nhận hàng. Quý khách lưu ý, sau khoảng thời gian này, chúng tôi sẽ không hỗ trợ bất kỳ 
                trường hợp khiếu nại nào khác.
            </p>
        </div>
        
        <div class="policy-section">
            <h2 class="section-title">4. Giao hàng không thành công:</h2>
            <p class="section-content">
                Đơn hàng sau 03 lần không được giao thành công sẽ được tự động hoàn trả về kho của chúng tôi và không thể khôi phục lại. Đối với đơn hàng đã thanh toán, chúng tôi sẽ 
                thực hiện thủ tục hoàn tiền trong vòng 7 đến 45 ngày làm việc (không kể các ngày lễ, Thứ 7 và Chủ Nhật) sau khi xác nhận đơn hàng đó được hoàn về hợp lệ.
            </p>
        </div>
        
        <div class="policy-section">
            <h2 class="section-title">5. Không thể giao hàng:</h2>
            <p class="section-content">
                Đơn hàng đã được chuyển giao cho đơn vị vận chuyển nhưng không thể giao do trường hợp bất khả kháng thiên tai, dịch bệnh, ...) tại khu vực giao hàng, đơn sẽ được tự 
                động hoàn trả về kho của chúng tôi và không thể khôi phục lại. Đối với đơn hàng đã thanh toán, chúng tôi sẽ thực hiện thủ tục hoàn tiền trong vòng 7 đến 45 ngày làm 
                việc (không kể các ngày lễ, Thứ 7 và Chủ Nhật) sau khi xác nhận đơn hàng đó được hoàn về hợp lệ.
            </p>
        </div>
    </div>
@endsection