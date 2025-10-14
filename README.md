# 1. Clone repository
git clone https://github.com/<your-username>/online-test-system.git
cd online-test-system

# 2. Cài đặt dependencies
composer install

# 3. Tạo file môi trường
cp .env.example .env

# 4. Tạo key ứng dụng
php artisan key:generate

# 5. Cấu hình .env
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=online_test_system
# DB_USERNAME=root
# DB_PASSWORD=

# 6. Chạy migration và seed dữ liệu mẫu
php artisan migrate --seed


Seeder sẽ tạo:
1 kỳ thi mẫu cùng 25 câu hỏi (20 trắc nghiệm, 5 tự luận).
Cách Test:
php artisan test --filter=ExamFeatureTest

