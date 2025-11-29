<?php
$filename = "Quiz.txt";
$questions = []; // Mảng chứa các câu hỏi

if (file_exists($filename)) {
    $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    $current_question = [];
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line)) continue;

        if (strpos($line, "ANSWER:") !== false) {
            // Nếu gặp dòng ANSWER thì lưu đáp án và kết thúc câu hỏi hiện tại
            $current_question['answer'] = trim(substr($line, strpos($line, ":") + 1));
            $questions[] = $current_question;
            $current_question = []; // Reset cho câu mới
        } elseif (preg_match('/^[A-D]\./', $line)) {
            // Nếu dòng bắt đầu bằng A., B., C., D. -> Là đáp án gợi ý
            $current_question['options'][] = $line;
        } else {
            // Còn lại là nội dung câu hỏi (có thể gồm nhiều dòng)
            if (!isset($current_question['question'])) {
                $current_question['question'] = $line;
            } else {
                $current_question['question'] .= " " . $line;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bài tập 2 - Trắc nghiệm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5 mb-5">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0 text-center">BÀI THI TRẮC NGHIỆM</h3>
        </div>
        <div class="card-body">
            <form action="result.php" method="POST">
                <?php foreach ($questions as $index => $q): ?>
                    <div class="card mb-4 border-0">
                        <div class="card-body">
                            <h5 class="card-title">Câu <?= $index + 1 ?>: <?= htmlspecialchars($q['question']) ?></h5>
                            <?php if (isset($q['options'])): ?>
                                <?php foreach ($q['options'] as $option): ?>
                                    <?php 
                                        // Lấy ký tự đầu (A, B, C, D) làm value
                                        $optionKey = substr($option, 0, 1); 
                                    ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="question_<?= $index ?>" 
                                               value="<?= $optionKey ?>" id="q<?= $index ?>_<?= $optionKey ?>">
                                        <label class="form-check-label" for="q<?= $index ?>_<?= $optionKey ?>">
                                            <?= htmlspecialchars($option) ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg">Nộp bài</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>