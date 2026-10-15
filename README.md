# passnhanh.vn

Website giới thiệu (landing page) của **PassNhanh.vn** — chợ pass thông minh cho vé sự kiện, phòng khách sạn, nhà thuê, tiền cọc, dịch vụ và đồ đạc.

Repo này tách riêng với web app MVP (`app-passnhanh`). Website là HTML/CSS/JS tĩnh, không cần build.

## Cấu trúc

```
index.html            Trang chủ
dieu-khoan.html       Điều khoản sử dụng (bản nháp)
quyen-rieng-tu.html   Chính sách quyền riêng tư (bản nháp)
404.html              Trang lỗi
assets/styles.css     Giao diện
assets/main.js        Menu mobile, tabs, link sang app
robots.txt, sitemap.xml
```

## Chạy local

```bash
python -m http.server 8765
```

Mở http://localhost:8765

## Cấu hình

- Các nút "Đăng Pass", "Khám phá tin"… trỏ tới web app qua hằng số `APP_URL` trong `assets/main.js` (mặc định `https://app.passnhanh.vn`).
- Email hỗ trợ đang để `hotro@passnhanh.vn`.

## Triển khai

Website đang được kết nối từ GitHub về hosting AZDIGI. Mọi thay đổi merge vào nhánh deploy sẽ lên web thật, nên hãy review kỹ trước khi merge.

File `.htaccess` (dùng cho Apache/LiteSpeed) sẽ:
- dùng `404.html` làm trang lỗi,
- tắt liệt kê thư mục,
- chặn truy cập `.git`, `.claude`, `README.md` nếu repo được clone thẳng vào `public_html`,
- bật cache và nén cho CSS/JS/SVG.

## Trước khi ra mắt

- Thay tin/dữ liệu minh họa bằng dữ liệu thật hoặc giữ nhãn "minh họa".
- Cho luật sư rà soát điều khoản và chính sách quyền riêng tư (đang là bản nháp).
- Công bố chính sách hoa hồng CTV chính thức.
- Thêm ảnh Open Graph (`og:image`) để chia sẻ lên mạng xã hội đẹp hơn.
