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

<h1 style="margin: 50px 50px">Cập nhật thông tin đồ án</h1>

<form action="{{ route('theses.update', $thesis->id) }}" method="POST" style="margin: 50px 50px">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label for="title">Tên đồ án</label>
        <input type="text" name="title" class="form-control" value="{{ $thesis->title }}" required>
    </div>
    <div class="form-group">
        <label for="student_id">Sinh viên</label>
        <select name="student_id" class="form-control" required>
            @foreach($students as $student)
            <option value="{{ $student->id }}" {{ $student->id == $thesis->student_id ? 'selected' : '' }}>{{ $student->first_name }} {{ $student->last_name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="program">Chương trình học</label>
        <input type="text" name="program" class="form-control" value="{{ $thesis->program }}" required>
    </div>
    <div class="form-group">
        <label for="supervisor">GV hướng dẫn</label>
        <input type="text" name="supervisor" class="form-control" value="{{ $thesis->supervisor }}" required>
    </div>
    <div class="form-group">
        <label for="submission_date">Ngày nộp</label>
        <input type="date" name="submission_date" class="form-control" value="{{ $thesis->submission_date }}">
    </div>
    <div class="form-group">
        <label for="defense_date">Ngày bảo vệ</label>
        <input type="date" name="defense_date" class="form-control" value="{{ $thesis->defense_date }}">
    </div>
    <button type="submit" class="btn btn-primary">Cập nhật</button>
</form>

</body>