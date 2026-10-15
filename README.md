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
