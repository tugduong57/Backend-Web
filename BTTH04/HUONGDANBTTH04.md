BTTH_04 – Hướng dẫn
1.	Tạo Migration cho bảng "students":
B1:  Chạy lệnh trong terminal – git bash
		php artisan make:migration create_students_table
B2: Định nghĩa migrations students:

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->string('email', 100)->unique();
            $table->string('student_number', 20)->unique();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('students');
    }
}


//1.	Tạo Migration cho bảng "theses":
B1:  Chạy lệnh trong terminal – git bash
		php artisan make:migration create_theses_table

B2: Định nghĩa migrations "theses"
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateThesesTable extends Migration
{
    public function up()
    {
        Schema::create('theses', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->string('program', 255);
            $table->string('supervisor', 100);
            $table->text('abstract');
            $table->date('submission_date')->nullable();
            $table->date('defense_date')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('theses');
    }
}



2: Tạo Seeder cho bảng students và theses
B1: Tạo seeder cho bảng "students" và "theses" bằng bash
		php artisan make:seeder StudentsTableSeeder
		php artisan make:seeder ThesesTableSeeder

		
B2: Định nghĩa 2 file Seeder

<!-- File Students	 -->
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class StudentsTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 10) as $index) {
            DB::table('students')->insert([
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'email' => $faker->unique()->safeEmail,
                'student_number' => 'SV' . $faker->unique()->numberBetween(1000, 9999),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
	
<!-- File theses -->
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ThesesTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 10) as $index) {
            DB::table('theses')->insert([
                'title' => $faker->sentence(6),
                'student_id' => $faker->numberBetween(1, 10),
                'program' => $faker->randomElement(['Information Systems', 'Software Engineering', 'Data Science']),
                'supervisor' => $faker->name,
                'abstract' => $faker->paragraph(5),
                'submission_date' => $faker->date(),
                'defense_date' => $faker->date(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}


<!-- Sau khi hoàn thiện về CSDL, sau đây tôi sẽ nêu những phần khác biệt so với bài Hướng dẫn CRUD ví dụ POST - và mức độ khác biệt từ ít đến nhiều! -->

1. ROUTER 
    Được chia thành các cặp làm việc với Modal hoặc với Views tương ứng.
    - Làm việc với View: Index, Create, Edit
    - Làm việc với Modal: Store, Update, Destroy
2. MODEL
    Ngoài việc định nghĩa các cột có thể điền (fillable)
    Cần định nghĩa mối quan hệ với Student (thesis thuộc về một sinh viên)
    
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

3. CONTROLLER
    Trong file Controller đã chia thành 02 file Index.
    $theses = Thesis::with('student')->get(); // Lấy dữ liệu đồ án và sinh viên liên quan

    // Sử dụng paginate thay vì all()
    $theses = Thesis::with('student')->paginate(5); // Lấy 5 bản ghi mỗi trang

    Và trong code có phần
    return redirect()->route('theses.index')->with('success', 'Đồ án đã được thêm thành công!'); 
    Được truyền dữ liệu vào (session('success')) để hiện thị (Quan sát kĩ trong Views)
4. VIEWS

    I. Views index
    - Lưu ý các button để router sang view khác thông qua Controller - Create, Edit, Destroy
    - Lưu ý cách chia phân trang sử dụng 
        <!-- <div class="d-flex justify-content-center">
				{{ $theses->links('pagination::bootstrap-4') }}
		</div> -->
    - Lưu ý ẩn/hiện modal Delete và form Delete khi ấn vào nút "XÓA" trong Modal "deleteModal{{ $thesis->id }}?

    II. View Create và View Edit
    - Lưu ý phần Dropdownlist được tạo bởi "Tên sinh viên" - liên kết khóa ngoại với bảng Students. 
    - Lưu ý các form actions được tạo để liên kết với Controller "store, update"