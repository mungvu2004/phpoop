@extends('client.layouts.main')

@push('styles')
    <link rel="stylesheet" href="{{ file_url('assets/client/return-policy.css') }}">
@endpush

@section('content')
    <div class="return-policy-container">
        <h1 class="policy-title">Chính sách trả hàng</h1>
        
        <p class="policy-intro">
            Chúng tôi trân trọng sự tin tưởng của khách hàng khi đặt mua các sản phẩm tại https://teelab.vn/. Vì vậy, Chúng tôi cam kết các sản phẩm được giao đến khách hàng là 
            hàng mới, đảm bảo chất lượng và 100% chính hãng. Dòng thời, sản phẩm sẽ được đóng gói và đảm bảo nguyên vẹn khi được giao tới khách hàng.
        </p>
        
        <p class="policy-intro">
            Các đơn hàng được chấp nhận trả hoàn với các điều kiện cụ thể:
        </p>
        
        <div class="policy-section">
            <h2 class="section-title">1. Điều kiện trả hàng:</h2>
            
            <div class="section-content">
                <p>1.1. Hoàn trả ngay khi nhận hàng: Nếu quý khách phát hiện kiện hàng có dấu hiệu ướt, rách, móp méo, không còn nguyên vẹn hoặc sai thông tin người nhận, quý khách 
                    vui lòng từ chối nhận hàng ngay tại thời điểm nhận hàng.</p>
                    
                <p>1.2. Với trường hợp sau khi nhận hàng và đã thanh toán cho đơn vị vận chuyển, bạn có quyền yêu cầu trả hàng khi mở kiện hàng và phát hiện một trong các lỗi bên dưới:</p>
                
                <ul class="error-list">
                    <li>- Sản phẩm bị rách bao bì, vỡ hỏng trong quá trình vận chuyển.</li>
                    <li>- Sản phẩm bị giao sai, thiếu phụ kiện, thiếu quà tặng đi kèm.</li>
                    <li>- Sản phẩm chuyển nhầm hàng, nhầm màu so với đơn đặt.</li>
                    <li>- Kiện hàng không thực hiện đồng kiểm khi nhận hàng.</li>
                </ul>
                
                <p>1.3. Các trường hợp sản phẩm bị lỗi từ phía sản xuất.</p>
                
                <p>Trong các trường hợp còn lại, Chúng tôi rất tiếc vì không thể hỗ trợ chính sách đổi trả hàng.</p>
            </div>
        </div>
        
        <div class="policy-section">
            <h2 class="section-title">2. Phương thức hoàn tiền:</h2>
            
            <div class="section-content">
                <p>Đối với đơn hàng hoàn trả do lỗi toàn bộ đơn, Chúng tôi hỗ trợ chính sách trả hàng và hoàn tiền qua tài khoản ngân hàng.</p>
                <p>Đối với đơn hàng hoàn trả do lỗi một phần, Chúng tôi hỗ trợ đổi trả sản phẩm (nếu sản phẩm còn hàng) hoặc hoàn tiền qua tài khoản ngân hàng.</p>
            </div>
        </div>
        
        <div class="policy-section">
            <h2 class="section-title">3. Cách thức trả hàng:</h2>
            
            <div class="section-content">
                <p>Quý khách vui lòng thông báo ngay cho Chúng tôi trong vòng 24 giờ (không tính Thứ 7, Chủ Nhật và các ngày lễ theo quy định) kể từ thời điểm nhận hàng. Chúng tôi có 
                    quyền từ chối hỗ trợ mọi khiếu nại của sản phẩm trong trường hợp quý khách thông báo sau thời gian này.</p>
                
                <p>Khách hàng vui lòng thực hiện theo các bước sau để quá trình hoàn trả đơn hàng được hợp lệ:</p>
                
                <p>3.1. Truy cập website https://teelab.vn/ hoặc liên hệ qua số điện thoại 0865.539.083 để được bộ phận CSKH hướng dẫn.</p>
                
                <p>3.2. Nếu yêu cầu hoàn trả hợp lệ, trong vòng 48 giờ tiếp theo, khách hàng vui lòng lựa chọn hình thức hoàn trả tại website https://teelab.vn/.</p>
                
                <p>3.3. Nơi dung hoàn trả và địa chỉ gửi hàng:</p>
                
                <div class="address-info">
                    <p>Họ kính danh: Võ Thị Quỳnh Anh</p>
                    <p>Địa chỉ: Số 235, Đường Quang Trung, Tổ 7, Phường Tân Thịnh, Thành phố Thái Nguyên, Tỉnh Thái Nguyên, Việt Nam</p>
                    <p>Email: teelab@gmail.com</p>
                    <p>Điện thoại: 0865539083</p>
                    <p>Nội dung: Hoàn trả đơn hàng số xxxxxx</p>
                </div>
                
                <p>3.4. Sau 10 ngày kể từ ngày đơn hàng được xác nhận hoàn trả và Chúng tôi vẫn chưa nhận được hàng hóa hoàn về, Chúng tôi sẽ từ chối giải quyết mọi khiếu nại và xem 
                    như khách hàng không còn nhu cầu hoàn trả đối với đơn hàng này.</p>
                
                <p>3.5. Khi nhận được đơn hàng hoàn trả, Chúng tôi sẽ tiến hàng kiểm tra để đảm bảo đơn hàng được trả về thỏa mãn các điều kiện tại mục 4.1. Khách hàng sẽ nhận được 
                    thông báo tới tài khoản đã đặt hàng trong vòng 7 ngày tiếp theo.</p>
                
                <p>3.5.1. Nếu đơn hàng hoàn về hợp lệ, khách hàng sẽ nhận tiền hoàn về tài khoản ngân hàng từ 05-15 ngày làm việc.</p>
                
                <p>3.5.2. Trong trường hợp đơn hàng KHÔNG hợp lệ, Chúng tôi sẽ liên hệ với quý khách để đề xuất hướng giải quyết tiếp theo.</p>
            </div>
        </div>
        
        <div class="policy-section">
            <h2 class="section-title">4. Đơn hàng trả về hợp lệ:</h2>
            
            <div class="section-content">
                <p>4.1. Tình trạng Đơn hàng trả về:</p>
                
                <ul class="condition-list">
                    <li>- Sản phẩm đúng như trong miêu tả đã điền trong phiếu "Yêu cầu trả hàng" và còn nguyên tình trạng ban đầu khi nhận hàng.</li>
                    <li>- Đơn hàng phải được trả về toàn bộ về sản phẩm trong đơn hàng, bao gồm hàng mua, phụ kiện và quà tặng kèm.</li>
                    <li>- Mỗi đơn hàng chỉ có thể yêu cầu trả hàng tối đa 01 lần.</li>
                </ul>
                
                <p>4.2. Đơn hàng trả về không hợp lệ nếu:</p>
                
                <ul class="invalid-list">
                    <li>- Chưa cung cấp thông tin hoặc cung cấp không đầy đủ/không chính xác với thông tin đã cung cấp theo "Yêu cầu trả hàng" tại website https://teelab.vn/.</li>
                    <li>- Quá 10 ngày kể từ ngày Chúng tôi xác nhận "Yêu cầu trả hàng" mà vẫn chưa nhận được đơn hàng hoàn về.</li>
                    <li>- Sản phẩm hoàn trả không đúng/không đủ với thông tin đã cung cấp tại phiếu "Yêu cầu trả hàng": thiếu sản phẩm, thiếu quà tặng…của đơn hàng.</li>
                    <li>- Các sản phẩm trong khống đạt tiêu chuẩn hoàn trả tại mục 4.1, sau bước kiểm duyệt của Chúng tôi ngay khi nhận đơn hàng hoàn trả.</li>
                </ul>
                
                <p>Số tiếp xếp đổ trả đổi/hoàn trả can thiết sẽ được xác định theo từng trường hợp. Vui lòng đảm bảo tất cả thông tin yêu cầu đổi trả là đầy đủ và chính xác để Chúng tôi 
                    hỗ trợ việc hoàn trả trong thời gian ngắn nhất.</p>
            </div>
        </div>
        
        <div class="policy-section">
            <h2 class="section-title">5. Chờ nhận hoàn tiền</h2>
            
            <div class="section-content">
                <p>Nếu sản phẩm hoàn trả thỏa mãn các điều kiện tại mục 4, trong vòng 05-15 ngày (ngoại trừ Thứ 7, Chủ Nhật và các ngày lễ), Chúng tôi sẽ xử lý việc hoàn tiền qua tài 
                    khoản ngân hàng quý khách đã sử dụng đặt hàng, kể từ ngày đơn hàng được xác nhận hoàn trả.</p>
                
                <p>Nếu sản phẩm không thỏa mãn các điều kiện tại mục 4, Chúng tôi rất tiếc không thể hoàn tiền cho quý khách. Mọi phản hồi về hàng hóa khác vui các lý do bên trên, vui 
                    lòng liên hệ bộ phận Chăm sóc khách hàng của Chúng tôi để được tư vấn thêm.</p>
            </div>
        </div>
    </div>
@endsection