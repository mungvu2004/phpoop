    document.addEventListener("DOMContentLoaded", function() {
    const newsData = [
    {
    id: 1,
    image: "storage/news/bao_quan.jpg",
    title: "Cách bảo quản áo đúng cách giúp áo luôn như mới ",
    content: `
    <p>Áo thun (áo phông) là một trong những món đồ thời trang phổ biến và dễ phối đồ nhất trong tủ quần áo. Tuy nhiên,
        để áo luôn giữ được form dáng đẹp, màu sắc bền và hình in không bị bong tróc theo thời gian, bạn cần lưu ý đến
        cách bảo quản và sử dụng đúng cách.</p>
    <img src="./storage/news/bao_quan.jpg" alt="Ảnh bảo quản áo thun">
    <p><i>Bảo quản áo thun</i></p>
    <p>Trước hết, không nên để áo thun ở những nơi ẩm ướt. Với đặc tính hút ẩm và hút nước tốt, áo thun rất dễ bị mốc
        hoặc xuất hiện các vết ố nếu để lâu trong môi trường không thông thoáng. Sau khi mặc áo để đi chơi, tập thể thao
        hoặc vận động nhiều ra mồ hôi, bạn nên giặt ngay để tránh mùi hôi và vi khuẩn tích tụ. Khi phơi, hãy lộn trái áo
        và chọn nơi mát mẻ, tránh ánh nắng trực tiếp vì tia UV có thể làm phai màu hình in. Để hạn chế tình trạng áo bị
        chảy xệ hoặc giãn vai, nên phơi ngang trên dây thay vì dùng móc treo.</p>
    <p>Về cách giặt, tốt nhất nên giặt tay, đặc biệt là với các loại áo có in hình. Nếu cần giặt bằng máy, hãy cho áo
        vào túi giặt, chọn chế độ giặt nhẹ và dùng nước lạnh để bảo vệ chất liệu vải. Không nên ngâm áo quá lâu trong xà
        phòng hoặc nước tẩy, và hạn chế sử dụng nước nóng vì có thể khiến vải co rút. Sau khi giặt, tránh vắt hoặc xoắn
        áo quá mạnh để giữ form dáng ban đầu.</p>
    <p>Gấp áo đúng cách cũng là một phần quan trọng trong việc bảo quản áo thun. Hãy trải phẳng áo trên bề mặt sạch, gấp
        hai tay áo vào trong, sau đó gấp phần thân từ dưới lên thành 2–3 nếp gọn gàng. Nếu muốn tiết kiệm không gian tủ,
        bạn có thể cuộn tròn áo sau khi gấp – cách này không chỉ gọn mà còn giúp áo ít nhăn.</p>
    <p>Trong quá trình sử dụng, bạn cũng nên lưu ý không mặc áo thun quá nhiều ngày liên tục mà không giặt. Khi vận động
        hoặc chơi thể thao, hãy chọn những chiếc áo có chất liệu co giãn và thấm hút mồ hôi tốt như cotton hoặc
        polyspandex. Tránh mặc áo khi còn ẩm, vì dễ gây ẩm mốc và ảnh hưởng đến làn da. Ngoài ra, hãy chọn áo phù hợp
        với từng hoàn cảnh – áo trơn đơn giản cho đi làm, áo in hình cá tính cho các buổi dạo phố. Hạn chế mang balo
        nặng khi mặc áo thun mỏng để tránh làm giãn phần vai áo.</p>
    <p>Chỉ với một vài thói quen nhỏ nhưng đúng cách, bạn đã có thể giúp chiếc áo thun yêu thích của mình luôn bền đẹp
        như mới qua nhiều lần sử dụng.</p>
    `
    },
    {
    id: 2,
    title: "5 kiểu phối đồ với áo phông cực đẹp",
    image: "storage/news/phoi_do.jpeg",
    content: `
    <p><b>Áo phông quần jeans</b></p>
    <img src= "./storage//news/ao_quan_jean.jpeg" alt="Ảnh áo thun phối quần jeans">
    <p><i>Áo phông phối quần jeans</i></p>
    <p>Áo phông và quần jeans là một bộ đôi bất bại, chưa từng đánh mất vị trí được người tiêu dùng ưa chuộng và lựa
        chọn nhất. Bất kể là quần skinny jeans, baggy, hay short jeans thì chắc chắn khi kết hợp cùng với áo phông chúng
        sẽ cực kỳ ăn khớp. Đây là combo cực kỳ trẻ trung, có tính ứng dụng cao mà lại phù hợp với tất cả mọi người.</p>
    <p>Áo phông trắng đơn giản phối với quần jeans đen: Trong khoảng thời gian gần đây, những kiểu áo phông trơn, áo
        slogan hoặc áo graphic mang hơi hướng retro là 3 item được yêu thích hơn cả. Như đã nói ở trên, vì có tính linh
        hoạt cao nên áo phông phù hợp với mọi giới tính và lứa tuổi.</p>
    <p><b>Áo phông và chân váy</b></p>
    <p>Mặc áo phông với chân váy có thể coi là cách mix đồ tương đối cổ điển nhưng vẫn cực kỳ hữu hiệu trong suốt nhiều
        năm vừa qua, vừa đem lại vẻ nữ tính và điệu đà cho bạn nữ mà vẫn giữ nguyên vẻ năng động và trẻ trung.</p>
    <p>Với những chiếc váy cầu kỳ và nhiều hoạ tiết thì bạn chỉ nên chọn những chiếc áo phông trơn hoặc áo có slogan đơn
        giản. Đó là cách phối hợp hài hoà, bù trừ lẫn nhau, tránh việc bạn trở thành tắc kè hoa trong mắt người đối
        diện.</p>
    <p><b>Áo phông và quần jogger</b></p>
    <p>Quần jogger vốn là chiếc quần mang dáng vẻ thoải mái và năng động. Và chỉ bẳng cách phối quần jogger với áo phông
        trơn thoải mái đơn giản là bạn đã có thể làm set đồ trở nên hoàn hảo, đem lại hơi thở trẻ trung và thoải mái cho
        người mặc.</p>
    <p><b>Áo phông và quần sooc</b></p>
    <p>Mùa hè nóng nực sắp tới thì chắc chắn một set đồ với áo phông và quần sooc nữ là sự kết hợp tuyệt vời cho các bạn
        gái. Hai item đều năng động và trẻ trung chắc chắn sẽ giúp các bạn xua tan đi cái nóng của mùa hè.</p>
    <p><b>Áo phông và áo khoác bò</b></p>
    <p>Đề cao tính linh hoạt và basic, sự kết hợp giữa item này phù hợp hầu hết với các dịp, từ đi học cho đến đi chơi với bạn bè. Set đồ tưởng chừng như đơn giản nhưng vẫn luôn đem đến phong cách thời trang và thời thượng cho người mặc.</p>
    `
    },
    {
    id: 3,
    title: "Các kiểu dáng áo phông",
    image: "storage/news/cac_kieu.jpg",
    content: `
    <p><b>Áo phông cổ tròn</b></p>
    <img src= "./storage/news/co_tron.jpeg"  alt="Ảnh áo phông cổ tròn">
    <p><i>Áo phông cổ tròn</i></p>
    <p>Đây là dáng áo phông phổ biến nhất vì nó đem lại cho người dùng sự thoải mái, linh hoạt trong việc phối đồ, đem lại phong cách trẻ trung và năng động cho người mặc. Áo phông cổ tròn hay áo phông cổ thuyền là một trong những item bán chạy nhất của hãng thời trang ONOFF nhờ các đặc tính thoải mái, thấm hút mồ hôi tốt,..</p>
    <p><b>Áo phông cổ tim</b></p>
    <img src= "./storage/news/co_tim.jpeg" alt="Ảnh áo phông cổ tim">
    <p><i>Áo phông cổ tim</i></p>
    <p>Áo phông cổ tim có thiết kế gần giống áo phông cổ chữ V nhưng cổ áo nông và tròn hơn. Chiếc áo này thường được thiết kế với dáng áo body, khoe các đường nét khoẻ khoắn của cơ thể, đem lại cảm giác phong lưu, quyến rũ.</p>
    <p><b>Áo phông cổ sơ mi</b></p>
    <img src="./storage/news/ao_phong_so_mi.jpeg"  alt="Ảnh áo phông cổ sơ mi">
    <p><i>Áo phông cổ sơ mi</i></p>
    <p>Áo phông cổ sơ mi còn có tên gọi là áo phông polo. Áo này thường được các chàng trai lựa chọn vào những dịp mang tính chất formal, lịch sự hơn, ví dụ như gặp đối tác, đi chơi golf,.. Áo polo còn có thể thay thế cho áo sơ mi khi bạn kết hợp với Vest để tạo cảm giác thanh lịch mà vẫn giữ vẻ trẻ trung.</p>
    <p><b>Áo phông thể thao</b></p>
    <p>Áo phông thể thao thường có kiểu dáng như áo phông cổ tròn nhưng được thiết kế với chất liệu thoải mái và thấm hút tốt hơn. </p>
    <p>Áo thun thể thao ngắn tay active cổ tròn nằm trong dòng Active wear bao gồm các mẫu trang phục được thiết kế phù hợp với các hoạt động vận động, thể thao. Công nghệ thấm hút một chiều trên nền chất liệu cotton và chất liệu tổng hợp tạo cảm giác mềm, xốp, nhẹ, co giãn tốt, thấm hút và thoát hơi ẩm vượt trội, giữ cơ thể luôn khô thoáng, thoải mái. Đây là item không thể thiếu cho các bạn ưa thích tập luyện thể thao, hay các gym-er chăm chỉ.</p>
    <p><b>Áo phông cổ chữ V</b></p>
    <p>Đúng như cái tên, áo thun cổ chữ V có hình dáng cổ áo chữ V hơi cong. Chiếc áo này sẽ rất phù hợp với các chàng trai có mặt tròn và vai rộng vì áo thun cổ V sẽ giúp các bạn có vóc dáng trông thon gọn và cân đối hơn.</p>
    `},
    {
    id: 4,
    title: "Chất liệu làm áo phông",
    image: "storage/news/chat_lieu.png",
    content: `
    <img src="./storage/news/chat_lieu.png"  alt="Chất liệu làm áo phông">,
    <p><i>Chất liệu làm áo phông</i></p>
    <p><b>Cotton</b></p>
    <p>Vải cotton hay còn gọi là cotton 100%, được làm từ sợi bông thiên nhiên. Đây là chất liệu phổ biến nhất được chọn làm áo thun bởi vì áo thun được làm từ loại vải này có khả năng co giãn rất tốt, thấm hút mồ hôi nhanh, mặc rất mát nên mang đến cảm giác thoải mái và rất dễ chịu. Vải cotton được sử dụng nhiều trên áo thun cao cấp, có giá trị sử dụng lớn, phù hợp là sản phẩm biếu tặng.</p>
    <p><b>Polyester</b></p>
    <p>Polyester là sợi gồm 100% nylon với nhược điểm không thấm hút, dễ gây nóng, nến chất liệu này thường được pha cùng sợi Cotton thì thường có độ bền cao và ít bị nhàu, vải ít bị co khi sử dụng.
        Khi được pha thêm polyester nhằm tạo độ co giãn, các tính năng trên của áo thun sẽ giảm đi, song nó lại tạo ra những đường lượn, bó sát gợi cảm cho người mặc. Do vậy, áo thun chất liệu Polyester phụ nữ lại chuộng áo có chất co giãn để tăng thêm nét quyến rũ.
        </p>
    <p><b>TC</b></p>
    <p>Vải TC còn có tên gọi là vải cotton 35/65, vải được cấu tạo từ 35% sợi cotton và 65% sợi PE. Loại vải này có khả năng co giãn ở mức độ trung bình, tính hút ẩm ổn nhưng không bằng vải cotton. Vải được sử dụng phổ biến trong các loại áo thun đồng phục vì giá thành rẻ hơn.</p>
    <p><b>PE</b></p>
    <p>Vải PE được cấu tạo từ 100% sợi PE, đặc điểm nổi bật của loại vải này đó là rất bền màu, ít bị nhăn nhàu. Tuy nhiên, vải không co giãn, khả năng thấm hút mồ hôi tốt nên có cảm giác oi bức trong quá trình sử dụng. Do vậy, loại vải này thường được dùng để thiết kế các mẫu áo sự kiện dùng một lần.</p>
    <p><b>CVC</b></p>
    <p>Vải CVC hay còn có tên gọi khác là vải cotton 65/35, loại vải này được cấu tạo từ 65% sợi cotton và 35% sợi PE. Vải mang tính chất của cả 2 loại sợi cotton và PE nên có khả năng co giãn tương đối, mềm mại và có tính hút ẩm tốt. Vải được sử dụng nhiều trong các loại áo phông đồng phục nhà hàng, công ty, áo lớp.</p>
    `},
    {
    id: 5,
    title: "Áo phông là gì?",
    image: "storage/news/ao_phong T-shirt.jpg",
    content: `
    <img src="./storage/news/ao_phong_henley.jpeg"  alt="Áo phông">
    <p><i>Hình ảnh áo phông henley</i></p>
    <p>Có lẽ trong thời trang hàng ngày, áo phông là item không thể thiếu trong tủ quần áo của mỗi người. Nhờ sự linh hoạt trong sự phối đồ, áo phông hay còn gọi là áo thun phù hợp với mọi giới tính và mọi lứa tuổi từ già đến trẻ và với mọi vóc dáng. Chính vì vậy, tuy đã xuất hiện từ rất lâu trong lịch sử thời trang nhưng sức hút của áo phông vẫn chưa bao giờ giảm “nhiệt”, luôn giữ vững vị trí được ưa chuộng trong lòng khách hàng tiêu dùng. Nhưng không phải ai cũng biết rõ về chiếc áo thun yêu thích của mình. </p>
    <p><b>Áo phông là gì</b></p>
    <img src="./storage/news/ao_phong_so_mi.jpeg"  alt="Áo phông">
    <p><i>Hình ảnh áo phông sơ mi</i></p>
    <p>Áo thun (Phông) là một cụm từ chỉ một loại áo phổ biến, được gọi ở miền nam, còn một số tỉnh miền Bắc và các tỉnh khác thì chúng được gọi là áo phông hoặc áo T-shirt. Cái tên T-shirt bắt nguồn từ hình dáng của chiếc áo này có hình chữ T đơn giản với hai cánh tay áo ngắn và phần thân hình chữ nhật. Loại áo này được làm từ vải thun (vải cotton nguyên chất hoặc vải sợi cotton pha trộn cùng PE) do đó áo thun có đặc tính mềm mại và có khả năng co giãn. </p>
    `},
    {
    id: 6,
    title: "ƯU ĐÃI TRI ÂN",
    image: "storage/news/tri_an.png",
    content: `
    <img src="./storage/news/tri_an.png" alt="Khuyến mãi">
    <p><i>Khuyến mãi</i></p>
    <p>
        <strong>🎉 TUẦN LỄ VÀNG – TRI ÂN KHÁCH HÀNG THÂN THIẾT 🎉</strong><br><br>
        Nhằm gửi lời cảm ơn sâu sắc đến quý khách hàng đã luôn tin tưởng và đồng hành cùng <strong>PUREWARE</strong> trong suốt thời gian qua, chúng tôi hân hạnh giới thiệu chương trình ưu đãi đặc biệt mang tên <strong>"Tuần lễ vàng – Tri ân khách hàng thân thiết"</strong> với hàng loạt ưu đãi hấp dẫn chưa từng có.<br><br>
      
        🔸 <strong>Tặng ngay Voucher 300.000 VNĐ</strong><br>
        – Áp dụng cho mọi khách hàng khi mua sắm với hóa đơn từ <strong>5.000.000 VNĐ</strong> trở lên.<br>
        – Voucher có thể sử dụng trực tiếp hoặc dùng cho lần mua kế tiếp tùy theo điều kiện cụ thể của từng đơn hàng.<br><br>
      
        🔸 <strong>Giảm giá lên đến 50%</strong><br>
        – Hàng trăm sản phẩm trong hệ thống được giảm giá sâu từ 10% đến 50%.<br>
        – Không giới hạn số lượng sản phẩm và không phân biệt chủng loại – áp dụng cho cả sản phẩm mới và sản phẩm đang được ưa chuộng nhất hiện nay.<br><br>
      
        ⏰ <strong>Thời gian áp dụng:</strong> [Bạn điền thời gian cụ thể, ví dụ: từ 20/04 đến 27/04/2025]<br>
        📍 <strong>Áp dụng tại:</strong> Toàn bộ hệ thống cửa hàng Pureware và website chính thức.<br><br>
      
        💡 <strong>Lưu ý:</strong><br>
        – Chương trình có thể kết thúc sớm nếu hết quà tặng hoặc sản phẩm khuyến mãi.<br>
        – Không áp dụng đồng thời với các chương trình khuyến mãi khác, trừ khi có thông báo cụ thể.<br>
        – Voucher không có giá trị quy đổi thành tiền mặt và không hoàn lại khi đơn hàng bị hủy.<br><br>
      
        👉 Đây là dịp đặc biệt để quý khách hàng mua sắm thông minh, tiết kiệm và nhận về những ưu đãi xứng đáng. PUREWARE cam kết luôn đồng hành cùng quý khách trong từng sản phẩm, từng dịch vụ và từng trải nghiệm mua sắm.<br><br>
      
        <strong>Hãy nhanh tay sở hữu ưu đãi vàng ngay hôm nay!</strong>
      </p>
    `}

    ];

    const newsListEl = document.getElementById("news-list");
    const newsDetailEl = document.getElementById("news-detail");
    const detailTitleEl = document.getElementById("detail-title");
    const detailContentEl = document.getElementById("detail-content");

    // Debug - kiểm tra xem có tìm thấy phần tử hay không
    console.log("News list element:", newsListEl);

    // Hiển thị danh sách
    function renderNewsList() {
        if (!newsListEl) {
            console.error("Cannot find news-list element");
            return;
        }
        
        newsListEl.innerHTML = "";
        newsData.forEach(news => {
            const newsItem = document.createElement("div");
            newsItem.className = "news-item";
            
            if (news.image) {
                const imgEl = document.createElement("img");
                imgEl.src = news.image;
                imgEl.alt = news.title;
                imgEl.style.cursor = "pointer"; // Cho biết có thể click
                imgEl.onclick = () => showNewsDetail(news.id); // Bấm vào ảnh sẽ mở chi tiết
                newsItem.appendChild(imgEl);
            }
            
            const titleEl = document.createElement("h3");
            titleEl.textContent = news.title;
            titleEl.style.cursor = "pointer";
            titleEl.onclick = () => showNewsDetail(news.id);
            
            newsItem.appendChild(titleEl);
            newsListEl.appendChild(newsItem);
        });
    }
    // Hiển thị chi tiết
    function showNewsDetail(id) {
        const news = newsData.find(n => n.id === id);
        if (news) {
            detailTitleEl.textContent = news.title;
            detailContentEl.innerHTML = news.content;
            newsListEl.style.display = "none";
            newsDetailEl.style.display = "block";
        }
    }

    // Quay lại danh sách
    window.showNewsList = function() {
        newsDetailEl.style.display = "none";
        newsListEl.style.display = "flex";
    };
    

    // Load danh sách khi mở trang
    renderNewsList();

});

