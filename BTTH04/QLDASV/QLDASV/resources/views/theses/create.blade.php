<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initialscale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-
alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
integrity="sha384-
GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD"
crossorigin="anonymous">
<title>Posts</title>
</head>
<body>


    <h1 style="margin: 50px 50px">Thêm Đồ án mới</h1>
    <form action="{{ route('theses.store') }}" method="POST" style="margin: 50px 50px">
        @csrf
        <div class="mb-3">
            <label for="title" class="form-label">Tên đồ án</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        <div class="mb-3">
            <label for="student_id" class="form-label">Sinh viên</label>
            <select class="form-control" id="student_id" name="student_id" required>
                @foreach($students as $student)
                    <option value="{{ $student->id }}">{{ $student->first_name }} {{ $student->last_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="program" class="form-label">Chương trình học</label>
            <input type="text" class="form-control" id="program" name="program" required>
        </div>
        <div class="mb-3">
            <label for="supervisor" class="form-label">GV Hướng dẫn</label>
            <input type="text" class="form-control" id="supervisor" name="supervisor" required>
        </div>
        <div class="mb-3">
            <label for="abstract" class="form-label">Tóm tắt</label>
            <textarea class="form-control" id="abstract" name="abstract" rows="3" required></textarea>
        </div>
        <div class="mb-3">
            <label for="submission_date" class="form-label">Ngày nộp</label>
            <input type="date" class="form-control" id="submission_date" name="submission_date" required>
        </div>
        <div class="mb-3">
            <label for="defense_date" class="form-label">Ngày bảo vệ</label>
            <input type="date" class="form-control" id="defense_date" name="defense_date" required>
        </div>
        <button type="submit" class="btn btn-primary">Thêm</button>
    </form>

</body>