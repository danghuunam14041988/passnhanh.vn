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
assets/main.js        Menu mobile, tìm kiếm, lọc tin, link sang app
assets/img/           Ảnh (đang là ảnh minh họa cắt từ bản thiết kế)
.github/workflows/    Tự động deploy lên AZDIGI
robots.txt, sitemap.xml
```

## WordPress (theme `wp-theme/passnhanh`)

Website đang chuyển sang WordPress. Theme **PassNhanh** giữ nguyên giao diện hiện tại và cho phép quản lý nội dung trong trang quản trị:

- **Tin Pass**: tiêu đề, ảnh đại diện, giá gốc, giá pass, khu vực, hạn (tự ẩn khi hết hạn), nhãn "Cần Pass Gấp", liên kết liên hệ.
- **Nhu cầu**: hiện ở cột "Nhu cầu đang tìm nhiều".
- **Danh mục Pass**: 12 danh mục tạo sẵn, mỗi danh mục có biểu tượng emoji, màu và thứ tự.
- **Bài viết**: mục "Bài viết & Cẩm nang" và trang `/blog/`.
- **Giao diện → Tùy biến → PassNhanh**: tiêu đề và ảnh đầu trang, ảnh bản đồ, ảnh CTV, địa chỉ web app, email hỗ trợ, bật/tắt nội dung minh họa.
- **Giao diện → Menu**: menu đầu trang ("Menu chính") và chân trang.

Khi kích hoạt theme lần đầu, theme tự tạo: 12 danh mục, trang Điều khoản, trang Quyền riêng tư, trang chủ tĩnh và trang Blog (không ghi đè thứ đã có). Khi chưa có Tin Pass / Nhu cầu nào, trang chủ hiện nội dung minh họa có nhãn "Tin minh họa".

`.cpanel.yml` tự nhận biết: thư mục website có `wp-config.php` thì chỉ chép theme vào `wp-content/themes/passnhanh/`, chưa có thì chép bản HTML tĩnh như cũ. Vì vậy merge theme vào `main` không làm thay đổi web cho tới khi cài WordPress.

### Các bước chuyển sang WordPress (làm một lần, web gián đoạn khoảng 10 phút)

1. **Sao lưu**: cPanel → File Manager → thư mục `passnhanh.vn` → Compress, tải file nén về máy.
2. **Dọn thư mục web** `/home/umrhcgry/passnhanh.vn/`: xóa `index.html`, `404.html`, `dieu-khoan.html`, `quyen-rieng-tu.html`, `robots.txt`, `sitemap.xml`, thư mục `assets/`, file `.htaccess` và thư mục `.git` nếu có (bật "Show Hidden Files" để thấy file ẩn).
3. **Cài WordPress**: cPanel → WordPress Manager by Softaculous → Install. Chọn `https://passnhanh.vn`, để trống ô thư mục (cài vào gốc), ngôn ngữ Tiếng Việt, tự đặt tài khoản quản trị và mật khẩu mạnh.
4. **Chép theme**: cPanel → Git Version Control → Manage → Pull or Deploy → **Update from Remote** → **Deploy HEAD Commit** (hoặc để GitHub Actions tự chạy).
5. **Kích hoạt theme**: vào `https://passnhanh.vn/wp-admin` → Giao diện → Giao diện → **PassNhanh** → Kích hoạt.
6. **Đường dẫn đẹp**: Cài đặt → Đường dẫn tĩnh → chọn "Tên bài viết" → Lưu thay đổi.
7. **Ảnh thật**: Giao diện → Tùy biến → PassNhanh → thay ảnh đầu trang, bản đồ, CTV.
8. **Nội dung**: thêm Tin Pass, Nhu cầu, bài viết. Tin minh họa tự ẩn khi có tin thật.

Sau khi chuyển, mỗi lần sửa theme chỉ cần merge vào `main` và deploy; WordPress tự nhận CSS/JS mới (phiên bản theo thời điểm sửa file).

### Blog WordPress tại passnhanh.vn/blog (đang dùng)

Trang chủ vẫn là HTML tĩnh; riêng Blog chạy WordPress cài trong thư mục con `blog`. Theme PassNhanh tự nhận "chế độ Blog" khi địa chỉ WordPress có đường dẫn con: trang `/blog/` hiện danh sách bài viết, menu và logo trỏ về `https://passnhanh.vn`, không tạo Tin Pass/Nhu cầu/Danh mục.

Cài một lần:

1. cPanel → **WordPress Manager by Softaculous** → **Install**. Chọn `https://passnhanh.vn`, ô thư mục (In Directory) ghi `blog`, ngôn ngữ **Tiếng Việt**, tự đặt tài khoản quản trị và mật khẩu mạnh (không dùng tên `admin`).
2. cPanel → **Git Version Control** → Manage → Pull or Deploy → **Update from Remote** → **Deploy HEAD Commit** (`.cpanel.yml` thấy `blog/wp-config.php` nên chép theme vào `blog/wp-content/themes/passnhanh/`).
3. Vào `https://passnhanh.vn/blog/wp-admin` → Giao diện → Giao diện → **PassNhanh** → Kích hoạt. Theme tự tạo 7 chuyên mục (Cách pass nhanh, Kinh nghiệm mua pass, Chuyển nhượng đặt phòng, Kiểm tra voucher, Giao dịch an toàn, Hướng dẫn đăng tin, Hướng dẫn cộng tác viên), đặt đường dẫn bài viết dạng `/blog/ten-bai/`, chuyên mục dạng `/blog/chuyen-muc/ten-chuyen-muc/`.
4. Xóa bài mẫu "Hello world!" và trang mẫu của WordPress.

Đăng bài: Bài viết → Viết bài mới → nhập tiêu đề, nội dung, **Tóm tắt** (dùng làm mô tả trên Google, ~150 ký tự), **Ảnh đại diện** (ngang, ≥ 1200×630), chọn chuyên mục → Đăng.

SEO có sẵn trong theme: thẻ mô tả, canonical, Open Graph, dữ liệu có cấu trúc `BlogPosting` + `BreadcrumbList` + `Organization`, sitemap `/blog/wp-sitemap.xml` (đã khai báo trong `robots.txt`), trang tìm kiếm `noindex`, ẩn trang tác giả và danh sách tài khoản. Nếu sau này cài plugin SEO (Yoast, Rank Math…) theme tự tắt phần thẻ meta để không trùng.

Trang chủ HTML tự lấy 4 bài mới nhất từ `/blog/wp-json/` để hiện ở mục "Bài viết & Cẩm nang".

### Chạy thử theme trên máy

```bash
npx @wp-playground/cli@3.1.58 server --port=9400 --mount-dir wp-theme/passnhanh /wordpress/wp-content/themes/passnhanh
```

Mở http://127.0.0.1:9400/wp-admin → Giao diện → kích hoạt PassNhanh. (Trên Git Bash của Windows, thêm `MSYS_NO_PATHCONV=1` trước lệnh.)

## Chạy local

```bash
python .claude/serve.py
```

Mở http://localhost:8765

## Cấu hình

- Các nút "Đăng Pass", "Khám phá tin"… trỏ tới web app qua hằng số `APP_URL` trong `assets/main.js` (mặc định `https://app.passnhanh.vn`).
- Email hỗ trợ đang để `hotro@passnhanh.vn`.

## Triển khai

Website triển khai lên hosting AZDIGI (cPanel) qua **Git Version Control**, lấy từ nhánh `main`.

### Tự động (GitHub Actions)

Mỗi lần nhánh `main` có commit mới, workflow `.github/workflows/deploy.yml` tự chạy:

1. Gọi API cPanel để kho trong Git Version Control kéo code mới nhất của `main`.
2. Gọi API cPanel để deploy: chạy `.cpanel.yml`, copy file website vào `/home/umrhcgry/passnhanh.vn/` (document root của `passnhanh.vn`).
3. Kiểm tra `https://passnhanh.vn` đã chạy đúng `index.html` của commit vừa đẩy.

Xem kết quả ở tab **Actions** trên GitHub. Có thể chạy lại bằng tay: Actions → *Deploy to AZDIGI* → **Run workflow**.

Thiết lập một lần:

1. Trong cPanel → **Git Version Control**: đã có kho clone từ `https://github.com/danghuunam14041988/passnhanh.vn.git`, nhánh `main`. Ghi lại đường dẫn kho (Repository Path), ví dụ `/home/umrhcgry/repositories/passnhanh.vn`.
2. Trong cPanel → **Security → Manage API Tokens**: tạo token mới (ví dụ tên `github-deploy`), copy token.
3. Trên GitHub → repo → **Settings → Secrets and variables → Actions → New repository secret**, tạo 4 secret:

   | Tên | Giá trị |
   | --- | --- |
   | `CPANEL_HOST` | tên máy chủ cPanel, ví dụ `abc123.azdigihost.com` (xem ở thanh địa chỉ khi đăng nhập cPanel, bỏ `https://` và `:2083`) |
   | `CPANEL_USER` | `umrhcgry` |
   | `CPANEL_TOKEN` | token vừa tạo ở bước 2 |
   | `CPANEL_REPO_ROOT` | đường dẫn kho ở bước 1 |

Token cPanel có toàn quyền trên tài khoản hosting: chỉ lưu trong GitHub Secrets, không dán vào code hay chat. Nếu lộ, xóa token trong cPanel và tạo token mới.

### Bằng tay (khi cần)

cPanel → Git Version Control → **Manage** → **Pull or Deploy** → **Update from Remote** → **Deploy HEAD Commit**.

### Lưu ý

Chỉ các file của website được copy; `.git`, `.github`, `.claude`, `README.md`, `.cpanel.yml` ở lại trong kho của cPanel.

Khi thêm file hoặc trang mới ở gốc repo, nhớ thêm tên file vào `.cpanel.yml`. File bị xóa khỏi repo sẽ không tự xóa trên hosting.

Mọi thay đổi merge vào `main` và được deploy sẽ lên web thật, nên hãy review kỹ trước khi merge.

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
