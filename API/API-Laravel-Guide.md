# HƯỚNG DẪN API VỚI LARAVEL

---

## CHƯƠNG 8: CHUYỂN ĐỔI SANG JSON API

### 8.1. Khái Niệm API & RESTful

#### API là gì?

**API (Application Programming Interface)** là một bộ quy tắc và giao thức cho phép các ứng dụng khác nhau giao tiếp với nhau. Thay vì trả về HTML (như các ứng dụng web truyền thống), API trả về dữ liệu ở định dạng **JSON** hoặc **XML**, cho phép các client (web, mobile, desktop) tương tác với server một cách độc lập.

**Ví dụ:**
- Một ứng dụng React frontend gọi API để lấy danh sách bài viết
- Một ứng dụng mobile gọi API để đăng nhập người dùng
- Một dịch vụ bên thứ ba gọi API để lấy dữ liệu

#### RESTful API là gì?

**REST (Representational State Transfer)** là một phong cách kiến trúc API dựa trên các nguyên tắc sau:

| Nguyên tắc | Mô tả |
|-----------|-------|
| **Resource-based** | Mỗi tài nguyên (users, posts, comments) được biểu diễn qua URL |
| **HTTP Methods** | Sử dụng GET, POST, PUT, DELETE để chỉ định hành động |
| **Stateless** | Mỗi request độc lập, không phụ thuộc vào session trước đó |
| **Representation** | Dữ liệu được trả về ở định dạng JSON hoặc XML |

#### HTTP Methods trong RESTful API

| HTTP Method | Hành động | Ví dụ |
|-----------|----------|-------|
| **GET** | Lấy dữ liệu | `GET /api/posts` → Lấy tất cả bài viết |
| **POST** | Tạo mới dữ liệu | `POST /api/posts` → Tạo bài viết mới |
| **PUT/PATCH** | Cập nhật dữ liệu | `PUT /api/posts/1` → Cập nhật bài viết ID 1 |
| **DELETE** | Xóa dữ liệu | `DELETE /api/posts/1` → Xóa bài viết ID 1 |

#### Lợi ích của API

1. **Tách biệt Frontend và Backend**: Frontend và Backend phát triển độc lập
2. **Tái sử dụng**: Một API có thể phục vụ nhiều client (web, mobile, desktop)
3. **Dễ bảo trì**: Thay đổi backend không ảnh hưởng đến frontend
4. **Linh hoạt**: Dễ thêm các client mới mà không cần thay đổi server

---

### 8.2. Định Tuyến API (api.php) và API Controllers

#### Định Tuyến API trong routes/api.php

Trong Laravel, tất cả các route API được định nghĩa trong file `routes/api.php`. Khác với `routes/web.php`, các route API:
- Không có session state
- Có prefix `/api` mặc định
- Không bao gồm CSRF protection (vì API là stateless)

**Cấu trúc mặc định của routes/api.php:**

```php
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\UserController;

// Prefix /api và middleware 'api' được áp dụng tự động
Route::middleware('api')->prefix('api')->group(function () {
    // Route cho Posts
    Route::apiResource('posts', PostController::class);
    
    // Route cho Users
    Route::apiResource('users', UserController::class);
    
    // Custom routes
    Route::post('posts/{post}/publish', [PostController::class, 'publish']);
    Route::get('posts/{post}/comments', [PostController::class, 'getComments']);
});
```

#### apiResource vs resource

Laravel cung cấp hai helper để tạo routes:

| Feature | `Route::resource()` | `Route::apiResource()` |
|---------|-------------------|------------------------|
| **Bao gồm routes** | GET, POST, PUT, DELETE, PATCH | GET, POST, PUT, DELETE, PATCH |
| **Routes index** | ✓ | ✓ |
| **Routes create** | ✓ (trả về form HTML) | ✗ |
| **Routes edit** | ✓ (trả về form HTML) | ✗ |
| **Routes store** | ✓ | ✓ |
| **Routes show** | ✓ | ✓ |
| **Routes update** | ✓ | ✓ |
| **Routes destroy** | ✓ | ✓ |

**Ví dụ so sánh:**

```php
// routes/web.php (cho web)
Route::resource('posts', PostController::class);
// Sinh ra: GET /posts, GET /posts/create, POST /posts, 
//         GET /posts/{id}, GET /posts/{id}/edit, PUT /posts/{id}, DELETE /posts/{id}

// routes/api.php (cho API)
Route::apiResource('posts', PostController::class);
// Sinh ra: GET /api/posts, POST /api/posts, 
//         GET /api/posts/{id}, PUT /api/posts/{id}, DELETE /api/posts/{id}
```

#### API Controllers

API Controllers khác với Web Controllers ở chỗ:
1. Không trả về View (Blade template)
2. Trả về JSON response
3. Không xử lý HTTP session
4. Xử lý HTTP status codes và lỗi một cách rõ ràng

**Tạo API Controller:**

```bash
php artisan make:controller Api/PostController --api
```

**Cấu trúc API Controller:**

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PostController extends Controller
{
    /**
     * GET /api/posts
     * Lấy tất cả bài viết
     */
    public function index()
    {
        $posts = Post::all();
        return response()->json([
            'success' => true,
            'data' => $posts,
            'message' => 'Danh sách bài viết'
        ], Response::HTTP_OK);
    }

    /**
     * POST /api/posts
     * Tạo bài viết mới
     */
    public function store(Request $request)
    {
        $post = Post::create($request->all());
        return response()->json([
            'success' => true,
            'data' => $post,
            'message' => 'Tạo bài viết thành công'
        ], Response::HTTP_CREATED);
    }

    /**
     * GET /api/posts/{id}
     * Lấy chi tiết một bài viết
     */
    public function show($id)
    {
        $post = Post::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $post,
            'message' => 'Chi tiết bài viết'
        ], Response::HTTP_OK);
    }

    /**
     * PUT /api/posts/{id}
     * Cập nhật bài viết
     */
    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $post->update($request->all());
        return response()->json([
            'success' => true,
            'data' => $post,
            'message' => 'Cập nhật bài viết thành công'
        ], Response::HTTP_OK);
    }

    /**
     * DELETE /api/posts/{id}
     * Xóa bài viết
     */
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();
        return response()->json([
            'success' => true,
            'message' => 'Xóa bài viết thành công'
        ], Response::HTTP_NO_CONTENT);
    }
}
```

---

### 8.3. Trả Về JSON & Eloquent API Resources

#### Trả về JSON Response

Laravel cung cấp helper `response()->json()` để trả về JSON:

```php
// Cách cơ bản
return response()->json($data);

// Với status code
return response()->json($data, 200);

// Với headers
return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE);
```

**Ví dụ JSON Response:**

```php
return response()->json([
    'success' => true,
    'data' => [
        'id' => 1,
        'name' => 'John Doe',
        'email' => 'john@example.com'
    ],
    'message' => 'Lấy dữ liệu thành công'
], 200);
```

**Response nhận được:**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com"
    },
    "message": "Lấy dữ liệu thành công"
}
```

#### Eloquent API Resources

Để có kiểm soát tốt hơn trên format dữ liệu trả về, Laravel cung cấp **API Resources**. Resource cho phép bạn:
1. Định dạng dữ liệu trước khi trả về
2. Ẩn các trường nhạy cảm (password, token, v.v.)
3. Thêm dữ liệu tính toán
4. Tái sử dụng format dữ liệu

**Tạo API Resource:**

```bash
php artisan make:resource PostResource
```

**Cấu trúc API Resource:**

```php
<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'author' => $this->user->name, // Relationship
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            // Không bao gồm trường nhạy cảm
        ];
    }
}
```

**Sử dụng Resource trong Controller:**

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Http\Resources\PostResource;
use Illuminate\Http\Response;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::all();
        return response()->json([
            'success' => true,
            'data' => PostResource::collection($posts),
            'message' => 'Danh sách bài viết'
        ], Response::HTTP_OK);
    }

    public function show($id)
    {
        $post = Post::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => new PostResource($post),
            'message' => 'Chi tiết bài viết'
        ], Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $post = Post::create($request->all());
        return response()->json([
            'success' => true,
            'data' => new PostResource($post),
            'message' => 'Tạo bài viết thành công'
        ], Response::HTTP_CREATED);
    }
}
```

**Response với Resource:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "title": "Học Laravel API",
            "content": "Hướng dẫn chi tiết...",
            "author": "Trần Văn A",
            "created_at": "2024-01-15 10:30:00",
            "updated_at": "2024-01-15 10:30:00"
        }
    ],
    "message": "Danh sách bài viết"
}
```

#### API Resource Collection

Khi muốn trả về một collection (danh sách) các resources với metadata:

```bash
php artisan make:resource PostCollection
```

```php
<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PostCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection,
            'meta' => [
                'total' => $this->collection->count(),
                'timestamp' => now()->format('Y-m-d H:i:s')
            ]
        ];
    }
}
```

---

## CHƯƠNG 9: XỬ LÝ REQUEST VÀ VALIDATION API

### 9.1. Xử Lý Request (POST, PUT, DELETE) cho API

#### Nhận dữ liệu từ Request

API nhận dữ liệu qua body của HTTP request, thường ở định dạng JSON:

```php
// POST /api/posts
// Header: Content-Type: application/json
// Body JSON:
{
    "title": "Bài viết mới",
    "content": "Nội dung bài viết"
}
```

**Truy cập dữ liệu từ Request trong Controller:**

```php
public function store(Request $request)
{
    // Lấy tất cả dữ liệu
    $all = $request->all();
    
    // Lấy dữ liệu cụ thể
    $title = $request->input('title');
    $content = $request->get('content');
    
    // Lấy dữ liệu nếu tồn tại
    $status = $request->input('status', 'draft'); // default value
    
    // Lấy dữ liệu từ JSON
    $data = $request->json()->all();
    
    // Lấy dữ liệu dưới dạng array
    $post = $request->only(['title', 'content']);
    
    // Lấy tất cả ngoại trừ một số field
    $post = $request->except(['_token']);
}
```

#### Xử lý POST request

```php
public function store(Request $request)
{
    // Validate dữ liệu (sẽ chi tiết ở phần 9.2)
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'content' => 'required|string',
        'user_id' => 'required|integer|exists:users,id'
    ]);

    // Tạo bài viết mới
    $post = Post::create($validated);

    return response()->json([
        'success' => true,
        'data' => new PostResource($post),
        'message' => 'Tạo bài viết thành công'
    ], Response::HTTP_CREATED); // 201 Created
}
```

#### Xử lý PUT/PATCH request

```php
public function update(Request $request, $id)
{
    $post = Post::findOrFail($id);

    // Validate dữ liệu
    $validated = $request->validate([
        'title' => 'sometimes|required|string|max:255',
        'content' => 'sometimes|required|string',
    ]);

    // Cập nhật bài viết
    $post->update($validated);

    return response()->json([
        'success' => true,
        'data' => new PostResource($post),
        'message' => 'Cập nhật bài viết thành công'
    ], Response::HTTP_OK); // 200 OK
}
```

**Khác nhau giữa PUT và PATCH:**

| Feature | PUT | PATCH |
|---------|-----|-------|
| **Ý nghĩa** | Thay thế toàn bộ resource | Cập nhật một phần resource |
| **Dữ liệu bắt buộc** | Tất cả trường | Chỉ những trường cần cập nhật |
| **Validation** | Bắt buộc đầy đủ | Tùy chọn (sometimes) |

**Ví dụ:**
```php
// PUT - Thay thế toàn bộ
// Request: { "title": "Tiêu đề mới" }
// Kết quả: Chỉ có title, content bị xóa

// PATCH - Cập nhật một phần
// Request: { "title": "Tiêu đề mới" }
// Kết quả: Title được cập nhật, content vẫn giữ nguyên
```

#### Xử lý DELETE request

```php
public function destroy($id)
{
    $post = Post::findOrFail($id);
    
    // Xóa bài viết
    $post->delete();

    return response()->json([
        'success' => true,
        'message' => 'Xóa bài viết thành công'
    ], Response::HTTP_NO_CONTENT); // 204 No Content
}
```

---

### 9.2. Validation (Xác Thực Dữ Liệu) bằng FormRequest

#### Giới thiệu FormRequest

Thay vì viết validation logic trực tiếp trong controller, Laravel cung cấp **FormRequest** - một class để tập trung xác thực dữ liệu.

**Lợi ích:**
1. Tách biệt logic validation khỏi controller
2. Dễ tái sử dụng
3. Dễ bảo trì
4. Có thể tùy chỉnh response lỗi

**Tạo FormRequest:**

```bash
php artisan make:request StorePostRequest
php artisan make:request UpdatePostRequest
```

#### Cấu trúc FormRequest

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    /**
     * Xác định xem user có quyền thực hiện request này không
     */
    public function authorize(): bool
    {
        return true; // Tất cả users được phép
    }

    /**
     * Định nghĩa các rules validation
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:10',
            'user_id' => 'required|integer|exists:users,id',
            'status' => 'in:draft,published,archived'
        ];
    }

    /**
     * Tùy chỉnh các thông báo lỗi
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Tiêu đề không được để trống',
            'title.max' => 'Tiêu đề không được vượt quá 255 ký tự',
            'content.required' => 'Nội dung không được để trống',
            'content.min' => 'Nội dung phải tối thiểu 10 ký tự',
            'user_id.exists' => 'User ID không tồn tại'
        ];
    }

    /**
     * Tùy chỉnh attribute names
     */
    public function attributes(): array
    {
        return [
            'title' => 'Tiêu đề',
            'content' => 'Nội dung',
            'user_id' => 'ID Tác giả'
        ];
    }
}
```

#### Sử dụng FormRequest trong Controller

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use App\Http\Resources\PostResource;

class PostController extends Controller
{
    public function store(StorePostRequest $request)
    {
        // Dữ liệu đã được validate tự động
        $validated = $request->validated();
        
        $post = Post::create($validated);

        return response()->json([
            'success' => true,
            'data' => new PostResource($post),
            'message' => 'Tạo bài viết thành công'
        ], 201);
    }

    public function update(UpdatePostRequest $request, $id)
    {
        $post = Post::findOrFail($id);
        $validated = $request->validated();
        
        $post->update($validated);

        return response()->json([
            'success' => true,
            'data' => new PostResource($post),
            'message' => 'Cập nhật bài viết thành công'
        ], 200);
    }
}
```

#### Các Rules Validation phổ biến

| Rule | Mô tả | Ví dụ |
|------|-------|-------|
| `required` | Trường bắt buộc phải có | `'name' => 'required'` |
| `string` | Dữ liệu phải là string | `'title' => 'string'` |
| `integer` | Dữ liệu phải là số nguyên | `'age' => 'integer'` |
| `email` | Dữ liệu phải là email hợp lệ | `'email' => 'email'` |
| `min:value` | Độ dài tối thiểu | `'password' => 'min:8'` |
| `max:value` | Độ dài tối đa | `'name' => 'max:255'` |
| `unique:table,column` | Dữ liệu phải unique | `'email' => 'unique:users,email'` |
| `exists:table,column` | Dữ liệu phải tồn tại trong DB | `'user_id' => 'exists:users,id'` |
| `in:value1,value2` | Dữ liệu phải là một trong các giá trị | `'status' => 'in:draft,published'` |
| `confirmed` | Phải có field _confirmation | `'password' => 'confirmed'` |
| `sometimes` | Validation tùy chọn | `'phone' => 'sometimes\|numeric'` |

**Ví dụ sử dụng multiple rules:**

```php
public function rules(): array
{
    return [
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8|confirmed',
        'age' => 'required|integer|min:18|max:120',
        'status' => 'sometimes|in:active,inactive,blocked'
    ];
}
```

#### Xử lý lỗi Validation trong API

Khi validation fails, Laravel tự động trả về 422 response với các lỗi:

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "title": [
            "Tiêu đề không được để trống"
        ],
        "content": [
            "Nội dung phải tối thiểu 10 ký tự"
        ]
    }
}
```

Để tùy chỉnh response lỗi validation:

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StorePostRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:10'
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $validator->errors()
        ], 422));
    }
}
```

---

## CHƯƠNG 10: KIỂM THỬ API

### 10.1. Giới thiệu Postman

#### Postman là gì?

**Postman** là một công cụ phổ biến để kiểm thử, phát triển và tài liệu hóa API. Nó cho phép bạn:
1. Gửi các HTTP request đến API
2. Xem response chi tiết
3. Lưu các request để tái sử dụng
4. Tạo collections để quản lý API
5. Kiểm thử tự động

#### Cài đặt Postman

1. Tải Postman từ: https://www.postman.com/downloads/
2. Cài đặt ứng dụng
3. Tạo tài khoản (tùy chọn)
4. Tạo Workspace mới

#### Giao diện Postman

```
┌─────────────────────────────────────────────────────────┐
│ Method | URL: http://localhost:8000/api/posts          │
├─────────────────────────────────────────────────────────┤
│ Headers | Body | Tests |                               │
├─────────────────────────────────────────────────────────┤
│ Key          | Value                                    │
│ Content-Type | application/json                        │
│ Accept       | application/json                        │
└─────────────────────────────────────────────────────────┘
│ { "title": "...", "content": "..." }                    │
└─────────────────────────────────────────────────────────┘
│ [SEND] Button                                          │
└─────────────────────────────────────────────────────────┘

Response:
┌─────────────────────────────────────────────────────────┐
│ Status: 200 OK | Time: 100ms | Size: 2.5KB            │
│ Body | Cookies | Headers |                             │
├─────────────────────────────────────────────────────────┤
│ { "success": true, "data": [...] }                     │
└─────────────────────────────────────────────────────────┘
```

---

### 10.2. Thực hành Kiểm thử CRUD Endpoints

#### Tạo Collection trong Postman

1. Click "+" để tạo collection mới
2. Đặt tên: "Post API"
3. Lưu collection

#### Kiểm thử GET - Lấy danh sách bài viết

**Request:**
```
GET http://localhost:8000/api/posts
Headers:
  Content-Type: application/json
  Accept: application/json
```

**Steps:**
1. Mở Postman
2. Chọn method: **GET**
3. Nhập URL: `http://localhost:8000/api/posts`
4. Click **Send**

**Expected Response (Status: 200):**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "title": "Bài viết 1",
            "content": "Nội dung bài viết 1",
            "author": "Người dùng A",
            "created_at": "2024-01-15 10:30:00",
            "updated_at": "2024-01-15 10:30:00"
        },
        {
            "id": 2,
            "title": "Bài viết 2",
            "content": "Nội dung bài viết 2",
            "author": "Người dùng B",
            "created_at": "2024-01-16 11:20:00",
            "updated_at": "2024-01-16 11:20:00"
        }
    ],
    "message": "Danh sách bài viết"
}
```

#### Kiểm thử POST - Tạo bài viết mới

**Request:**
```
POST http://localhost:8000/api/posts
Headers:
  Content-Type: application/json
Body (JSON):
{
    "title": "Bài viết mới từ Postman",
    "content": "Đây là nội dung bài viết được tạo từ Postman",
    "user_id": 1
}
```

**Steps:**
1. Chọn method: **POST**
2. Nhập URL: `http://localhost:8000/api/posts`
3. Tab Headers: Thêm `Content-Type: application/json`
4. Tab Body: Chọn **raw** → **JSON** → Nhập JSON data
5. Click **Send**

**Expected Response (Status: 201):**
```json
{
    "success": true,
    "data": {
        "id": 3,
        "title": "Bài viết mới từ Postman",
        "content": "Đây là nội dung bài viết được tạo từ Postman",
        "author": "Người dùng C",
        "created_at": "2024-01-17 14:50:00",
        "updated_at": "2024-01-17 14:50:00"
    },
    "message": "Tạo bài viết thành công"
}
```

#### Kiểm thử POST - Lỗi Validation

**Request:**
```
POST http://localhost:8000/api/posts
Body (JSON):
{
    "title": "",
    "content": "Quá ngắn"
}
```

**Expected Response (Status: 422):**
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "title": [
            "Tiêu đề không được để trống"
        ],
        "content": [
            "Nội dung phải tối thiểu 10 ký tự"
        ],
        "user_id": [
            "User ID không tồn tại"
        ]
    }
}
```

#### Kiểm thử GET - Lấy chi tiết bài viết

**Request:**
```
GET http://localhost:8000/api/posts/1
```

**Expected Response (Status: 200):**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "title": "Bài viết 1",
        "content": "Nội dung bài viết 1",
        "author": "Người dùng A",
        "created_at": "2024-01-15 10:30:00",
        "updated_at": "2024-01-15 10:30:00"
    },
    "message": "Chi tiết bài viết"
}
```

#### Kiểm thử PUT - Cập nhật bài viết

**Request:**
```
PUT http://localhost:8000/api/posts/1
Headers:
  Content-Type: application/json
Body (JSON):
{
    "title": "Bài viết 1 - Đã cập nhật",
    "content": "Nội dung bài viết đã được cập nhật",
    "user_id": 2
}
```

**Expected Response (Status: 200):**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "title": "Bài viết 1 - Đã cập nhật",
        "content": "Nội dung bài viết đã được cập nhật",
        "author": "Người dùng B",
        "created_at": "2024-01-15 10:30:00",
        "updated_at": "2024-01-17 15:00:00"
    },
    "message": "Cập nhật bài viết thành công"
}
```

#### Kiểm thử PATCH - Cập nhật một phần

**Request:**
```
PATCH http://localhost:8000/api/posts/1
Headers:
  Content-Type: application/json
Body (JSON):
{
    "title": "Tiêu đề đã sửa"
}
```

**Expected Response (Status: 200):**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "title": "Tiêu đề đã sửa",
        "content": "Nội dung bài viết đã được cập nhật",
        "author": "Người dùng B",
        "created_at": "2024-01-15 10:30:00",
        "updated_at": "2024-01-17 15:05:00"
    },
    "message": "Cập nhật bài viết thành công"
}
```

#### Kiểm thử DELETE - Xóa bài viết

**Request:**
```
DELETE http://localhost:8000/api/posts/3
```

**Expected Response (Status: 204):**
```
(No Content)
```

#### Lưu Request thành Collection

1. Sau khi gửi request, click **Save**
2. Chọn collection hoặc tạo mới
3. Đặt tên cho request: "Get All Posts"
4. Click **Save to Post API**

---

### 10.3. Ôn tập Giai đoạn 3

#### Các Concepts chính

| Concept | Mô tả |
|---------|-------|
| **API** | Interface giữa client và server để trao đổi dữ liệu |
| **REST** | Phong cách kiến trúc API sử dụng HTTP methods |
| **JSON** | Định dạng dữ liệu phổ biến cho API responses |
| **HTTP Methods** | GET, POST, PUT, DELETE, PATCH cho CRUD operations |
| **Status Codes** | 200, 201, 400, 404, 422, 500 để chỉ kết quả request |
| **apiResource** | Helper trong Laravel tạo 7 routes CRUD cơ bản |
| **API Controller** | Controller trả về JSON thay vì View |
| **API Resource** | Class định dạng dữ liệu trả về từ API |
| **FormRequest** | Class validation dữ liệu request |
| **Postman** | Công cụ kiểm thử API |

#### Quy trình phát triển API

1. **Định nghĩa Routes** trong `routes/api.php`
2. **Tạo API Controller** với các method CRUD
3. **Tạo Model** và **Migrations**
4. **Tạo FormRequest** cho validation
5. **Tạo API Resource** để format response
6. **Kiểm thử** bằng Postman

#### Checklist Hoàn thành Giai đoạn 3

- [ ] Hiểu khái niệm API và REST
- [ ] Tạo được API routes sử dụng apiResource
- [ ] Tạo được API Controllers
- [ ] Trả về JSON response đúng format
- [ ] Sử dụng API Resources để format dữ liệu
- [ ] Sử dụng FormRequest để validate dữ liệu
- [ ] Xử lý lỗi validation và trả về response phù hợp
- [ ] Kiểm thử API endpoints bằng Postman
- [ ] Hiểu sự khác nhau giữa PUT và PATCH
- [ ] Hiểu HTTP status codes phù hợp cho mỗi operation

---

## CHƯƠNG 11: BẢO MẬT & XÁC THỰC API

### 11.1. Khái niệm Stateful vs. Stateless

#### Stateful Authentication (Ứng dụng Web truyền thống)

**Stateful** nghĩa là server lưu trữ trạng thái (state) của user (session).

**Quy trình:**
1. User đăng nhập
2. Server tạo session và lưu thông tin vào server/database
3. Server gửi session ID cho client (qua cookie)
4. Client gửi session ID trong mỗi request tiếp theo
5. Server tra cứu session ID để xác thực

```
Client                          Server
  |                             |
  |-----> Đăng nhập ----------->|
  |                             | Tạo Session
  |<------ Set-Cookie: id -------|
  |                             |
  |-----> GET /posts ---------->|
  |    Cookie: id=123          |
  |<------ Response ------------|
```

**Đặc điểm:**
- Server cần lưu trữ session (memory, file, database)
- Không thể scale horiz
ontally (khó mở rộng trên nhiều server)
- Session sẽ timeout và hết hạn
- Phù hợp với web truyền thống

**Code Ví dụ:**
```php
// Tạo session
Session::put('user_id', $user->id);

// Kiểm tra session
if (Session::has('user_id')) {
    $user_id = Session::get('user_id');
}
```

#### Stateless Authentication (API)

**Stateless** nghĩa là server không lưu trữ bất kỳ trạng thái nào. Mỗi request chứa đầy đủ thông tin để xác thực.

**Quy trình (Token-based):**
1. User đăng nhập
2. Server tạo token (JWT hoặc OAuth) và gửi cho client
3. Client lưu token (localStorage, sessionStorage)
4. Client gửi token trong mỗi request (header Authorization)
5. Server validate token mà không cần lưu trữ

```
Client                          Server
  |                             |
  |-----> Đăng nhập ----------->|
  |                             | Tạo Token
  |<------ Token: abc123 --------|
  |                             |
  |-----> GET /posts ---------->|
  | Header: Authorization: Bearer abc123
  |<------ Response ------------|
```

**Đặc điểm:**
- Server không lưu trữ state
- Có thể scale horizontally (dễ mở rộng)
- Token có thời gian hết hạn
- Phù hợp với API và microservices

**Code Ví dụ:**
```php
// Token được gửi trong header
// Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...

// Server validate token
$user = Auth::user();
```

#### So sánh Stateful vs Stateless

| Feature | Stateful (Session) | Stateless (Token) |
|---------|------------------|------------------|
| **Server State** | Lưu session | Không lưu trữ |
| **Scalability** | Khó mở rộng | Dễ mở rộng |
| **Storage** | Server/DB | Client |
| **Transmission** | Cookie | Header (Authorization) |
| **CSRF Protection** | Cần CSRF token | Không cần |
| **Logout** | Xóa session | Vô hiệu hóa token |
| **Sử dụng** | Web truyền thống | API, Mobile, SPA |

---

### 11.2. Giới thiệu Laravel Sanctum (Token-based)

#### Laravel Sanctum là gì?

**Laravel Sanctum** là một package bảo mật do Laravel cung cấp, cho phép bạn:
1. Cấp API tokens cho users
2. Xác thực stateless API requests
3. Bảo vệ routes bằng middleware

#### Cài đặt Sanctum

```bash
# Cài đặt package
composer require laravel/sanctum

# Publish migrations
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"

# Chạy migrations
php artisan migrate
```

**Sau cài đặt, Sanctum tạo bảng `personal_access_tokens`:**
```sql
CREATE TABLE personal_access_tokens (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    tokenable_type VARCHAR(255),
    tokenable_id BIGINT,
    name VARCHAR(255),
    token VARCHAR(80) UNIQUE,
    abilities JSON,
    last_used_at TIMESTAMP NULL,
    expires_at TIMESTAMP NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

#### Cấu hình Sanctum trong Model

```php
<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasApiTokens;
    
    // ... các property khác
}
```

#### Middleware Authentication

Sanctum cung cấp middleware `sanctum` để bảo vệ routes:

```php
// routes/api.php
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    Route::apiResource('posts', PostController::class);
});
```

**Các middleware phổ biến:**
- `auth:sanctum` - Xác thực bằng Sanctum token
- `guest` - Route chỉ cho guests (không đăng nhập)

---

### 11.3. Triển khai (Đăng nhập, Đăng ký, Logout, Bảo vệ Routes)

#### API Đăng ký (Register)

**Tạo FormRequest:**

```bash
php artisan make:request RegisterRequest
```

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên không được để trống',
            'email.required' => 'Email không được để trống',
            'email.unique' => 'Email này đã tồn tại',
            'password.required' => 'Mật khẩu không được để trống',
            'password.min' => 'Mật khẩu phải tối thiểu 8 ký tự',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp'
        ];
    }
}
```

**Tạo API Controller:**

```bash
php artisan make:controller Api/AuthController --api
```

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    /**
     * POST /api/auth/register
     * Đăng ký user mới
     */
    public function register(RegisterRequest $request)
    {
        // Validate tự động từ RegisterRequest
        $validated = $request->validated();

        // Hash password
        $validated['password'] = Hash::make($validated['password']);

        // Tạo user mới
        $user = User::create($validated);

        // Tạo token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
                'token' => $token
            ],
            'message' => 'Đăng ký thành công'
        ], Response::HTTP_CREATED);
    }
}
```

**Cách sử dụng:**

```bash
# POST /api/auth/register
curl -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Trần Văn A",
    "email": "tranan@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

**Response:**
```json
{
    "success": true,
    "data": {
        "user": {
            "id": 1,
            "name": "Trần Văn A",
            "email": "tranan@example.com",
            "created_at": "2024-01-17 16:30:00",
            "updated_at": "2024-01-17 16:30:00"
        },
        "token": "1|abcdefghijklmnopqrstuvwxyz1234567890"
    },
    "message": "Đăng ký thành công"
}
```

#### API Đăng nhập (Login)

**Tạo FormRequest:**

```bash
php artisan make:request LoginRequest
```

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Email không được để trống',
            'email.email' => 'Email phải là định dạng hợp lệ',
            'password.required' => 'Mật khẩu không được để trống',
        ];
    }
}
```

**Trong AuthController:**

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    /**
     * POST /api/auth/login
     * Đăng nhập user
     */
    public function login(LoginRequest $request)
    {
        $validated = $request->validated();

        // Tìm user bằng email
        $user = User::where('email', $validated['email'])->first();

        // Kiểm tra user tồn tại và password đúng
        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email hoặc mật khẩu không đúng'
            ], Response::HTTP_UNAUTHORIZED);
        }

        // Tạo token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
                'token' => $token
            ],
            'message' => 'Đăng nhập thành công'
        ], Response::HTTP_OK);
    }
}
```

**Cách sử dụng:**

```bash
# POST /api/auth/login
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "tranan@example.com",
    "password": "password123"
  }'
```

**Response:**
```json
{
    "success": true,
    "data": {
        "user": {
            "id": 1,
            "name": "Trần Văn A",
            "email": "tranan@example.com"
        },
        "token": "1|abcdefghijklmnopqrstuvwxyz1234567890"
    },
    "message": "Đăng nhập thành công"
}
```

#### API Logout

```php
/**
 * POST /api/auth/logout
 * Đăng xuất user
 */
public function logout(Request $request)
{
    // Xóa token hiện tại
    $request->user()->currentAccessToken()->delete();

    return response()->json([
        'success' => true,
        'message' => 'Đăng xuất thành công'
    ], Response::HTTP_OK);
}
```

**Cách sử dụng:**

```bash
# POST /api/auth/logout
# Cần gửi token trong header
curl -X POST http://localhost:8000/api/auth/logout \
  -H "Authorization: Bearer 1|abcdefghijklmnopqrstuvwxyz1234567890" \
  -H "Content-Type: application/json"
```

#### Bảo vệ Routes với Middleware

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;

// Routes công khai
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});

// Routes bảo vệ - Yêu cầu xác thực
Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
    });
    
    Route::apiResource('posts', PostController::class);
});
```

#### Lấy thông tin User hiện tại

```php
/**
 * GET /api/auth/me
 * Lấy thông tin user đăng nhập
 */
public function me(Request $request)
{
    return response()->json([
        'success' => true,
        'data' => $request->user(),
        'message' => 'Thông tin user'
    ], Response::HTTP_OK);
}
```

#### Kiểm thử Auth Endpoints bằng Postman

**1. Register:**
```
POST http://localhost:8000/api/auth/register
Content-Type: application/json

{
    "name": "Trần Văn A",
    "email": "tranan@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

**2. Login:**
```
POST http://localhost:8000/api/auth/login
Content-Type: application/json

{
    "email": "tranan@example.com",
    "password": "password123"
}
```

**3. Get Current User (Cần token):**
```
GET http://localhost:8000/api/auth/me
Authorization: Bearer {token_received_from_login}
Content-Type: application/json
```

**4. Create Post (Cần token):**
```
POST http://localhost:8000/api/posts
Authorization: Bearer {token_received_from_login}
Content-Type: application/json

{
    "title": "Bài viết mới",
    "content": "Nội dung bài viết",
    "user_id": 1
}
```

**5. Logout:**
```
POST http://localhost:8000/api/auth/logout
Authorization: Bearer {token_received_from_login}
Content-Type: application/json
```

---

## CHƯƠNG 12: TÍCH HỢP FRONTEND & HOÀN THIỆN

### 12.1. Xử lý CORS (Cross-Origin Resource Sharing)

#### CORS là gì?

**CORS (Cross-Origin Resource Sharing)** là một cơ chế bảo mật của trình duyệt. Nó kiểm soát xem code từ một origin (domain, port) có thể truy cập tài nguyên từ một origin khác hay không.

**Ví dụ vấn đề CORS:**
```
Frontend: http://localhost:3000
Backend API: http://localhost:8000

Khi Frontend gọi API từ http://localhost:3000,
trình duyệt sẽ chặn vì:
- Domain khác (localhost)
- Port khác (3000 vs 8000)
```

**Quy trình CORS:**

```
Browser (http://localhost:3000)
        |
        | Gửi Preflight Request (OPTIONS)
        | Origin: http://localhost:3000
        ↓
Server (http://localhost:8000)
        |
        | Trả về CORS headers
        | Access-Control-Allow-Origin: http://localhost:3000
        ↓
Browser
        |
        | Nếu được phép → Gửi Actual Request (GET/POST)
        | Nếu không → Chặn request
        ↓
```

#### Cấu hình CORS trong Laravel

Laravel cung cấp file `config/cors.php` để cấu hình CORS:

```php
<?php

return [
    'paths' => ['api/*'],  // Routes áp dụng CORS
    
    'allowed_methods' => ['*'],  // Cho phép tất cả HTTP methods
    
    'allowed_origins' => [
        'http://localhost:3000',      // Frontend development
        'http://localhost:5173',       // Vite dev server
        'https://example.com'          // Production
    ],
    
    'allowed_origins_patterns' => [
        '#http://localhost:\d+#'       // Cho phép localhost với bất kỳ port
    ],
    
    'allowed_headers' => ['*'],  // Cho phép tất cả headers
    
    'exposed_headers' => [
        'Content-Length',
        'X-JSON-Response',
        'Authorization'
    ],
    
    'max_age' => 86400,  // Cache preflight response (24 hours)
    
    'supports_credentials' => true,  // Cho phép cookies/auth headers
];
```

#### Cấu hình CORS cho từng routes

Nếu muốn điều khiển CORS cho route cụ thể:

```php
<?php

use Illuminate\Support\Facades\Route;

// Cho phép CORS cho tất cả origins
Route::middleware('cors')->group(function () {
    Route::apiResource('posts', PostController::class);
});

// Hoặc cấu hình custom middleware
Route::middleware('cors:http://localhost:3000')->group(function () {
    // ...
});
```

#### Ví dụ CORS Headers

**Preflight Request (OPTIONS):**
```http
OPTIONS /api/posts HTTP/1.1
Host: localhost:8000
Origin: http://localhost:3000
Access-Control-Request-Method: POST
Access-Control-Request-Headers: Content-Type
```

**Server Response:**
```http
HTTP/1.1 200 OK
Access-Control-Allow-Origin: http://localhost:3000
Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS
Access-Control-Allow-Headers: Content-Type, Authorization
Access-Control-Max-Age: 86400
```

**Actual Request (POST):**
```http
POST /api/posts HTTP/1.1
Host: localhost:8000
Origin: http://localhost:3000
Content-Type: application/json
Authorization: Bearer token123

{
    "title": "New Post",
    "content": "Content here"
}
```

#### Kiểm thử CORS với JavaScript

```javascript
// Frontend: http://localhost:3000

// Gọi API từ backend: http://localhost:8000
fetch('http://localhost:8000/api/posts', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer ' + token
    },
    body: JSON.stringify({
        title: 'New Post',
        content: 'Content here'
    })
})
.then(response => response.json())
.then(data => console.log(data))
.catch(error => console.error('Error:', error));
```

---

### 12.2. Hướng dẫn Kết nối Frontend với API

#### Kiến trúc Frontend - Backend

```
Frontend (React/Vue)          Backend (Laravel API)
    |                              |
    | GET /api/posts               |
    |----------------------------->|
    |                              | Query DB
    |                              |
    | JSON Response                |
    |<-----------------------------|
    |                              |
    | Display Data                 |
    |                              |
```

#### Ví dụ Frontend với JavaScript Fetch API

**1. Lấy danh sách bài viết:**

```javascript
// Hàm lấy danh sách posts
async function getPosts() {
    try {
        const response = await fetch('http://localhost:8000/api/posts', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });
        
        if (!response.ok) {
            throw new Error('Failed to fetch posts');
        }
        
        const data = await response.json();
        displayPosts(data.data);
    } catch (error) {
        console.error('Error:', error);
    }
}

// Hàm hiển thị posts
function displayPosts(posts) {
    const container = document.getElementById('posts');
    container.innerHTML = posts.map(post => `
        <div class="post">
            <h2>${post.title}</h2>
            <p>${post.content}</p>
            <small>by ${post.author}</small>
        </div>
    `).join('');
}

// Gọi hàm khi page load
document.addEventListener('DOMContentLoaded', getPosts);
```

**2. Tạo bài viết mới:**

```javascript
// Form submit handler
document.getElementById('postForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const formData = {
        title: document.getElementById('title').value,
        content: document.getElementById('content').value,
        user_id: 1  // ID của user đang đăng nhập
    };
    
    try {
        const response = await fetch('http://localhost:8000/api/posts', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + localStorage.getItem('token')
            },
            body: JSON.stringify(formData)
        });
        
        if (!response.ok) {
            const errors = await response.json();
            alert('Lỗi: ' + JSON.stringify(errors.errors));
            return;
        }
        
        alert('Tạo bài viết thành công!');
        document.getElementById('postForm').reset();
        getPosts();  // Tải lại danh sách
    } catch (error) {
        console.error('Error:', error);
    }
});
```

**3. Cập nhật bài viết:**

```javascript
async function updatePost(postId, updatedData) {
    try {
        const response = await fetch(
            `http://localhost:8000/api/posts/${postId}`,
            {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                },
                body: JSON.stringify(updatedData)
            }
        );
        
        if (!response.ok) {
            throw new Error('Failed to update post');
        }
        
        alert('Cập nhật thành công!');
        getPosts();
    } catch (error) {
        console.error('Error:', error);
    }
}
```

**4. Xóa bài viết:**

```javascript
async function deletePost(postId) {
    if (!confirm('Bạn có chắc muốn xóa?')) {
        return;
    }
    
    try {
        const response = await fetch(
            `http://localhost:8000/api/posts/${postId}`,
            {
                method: 'DELETE',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                }
            }
        );
        
        if (!response.ok) {
            throw new Error('Failed to delete post');
        }
        
        alert('Xóa thành công!');
        getPosts();
    } catch (error) {
        console.error('Error:', error);
    }
}
```

**5. Đăng nhập và lưu token:**

```javascript
async function login(email, password) {
    try {
        const response = await fetch('http://localhost:8000/api/auth/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ email, password })
        });
        
        if (!response.ok) {
            alert('Đăng nhập thất bại!');
            return;
        }
        
        const data = await response.json();
        
        // Lưu token vào localStorage
        localStorage.setItem('token', data.data.token);
        localStorage.setItem('user', JSON.stringify(data.data.user));
        
        alert('Đăng nhập thành công!');
        window.location.href = '/dashboard';
    } catch (error) {
        console.error('Error:', error);
    }
}

// Form login
document.getElementById('loginForm').addEventListener('submit', (e) => {
    e.preventDefault();
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    login(email, password);
});
```

#### Ví dụ Frontend với React

```javascript
import React, { useState, useEffect } from 'react';

function PostsComponent() {
    const [posts, setPosts] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const [title, setTitle] = useState('');
    const [content, setContent] = useState('');

    // Lấy danh sách posts
    useEffect(() => {
        const fetchPosts = async () => {
            try {
                const response = await fetch('http://localhost:8000/api/posts');
                if (!response.ok) throw new Error('Failed to fetch');
                const data = await response.json();
                setPosts(data.data);
            } catch (err) {
                setError(err.message);
            } finally {
                setLoading(false);
            }
        };
        
        fetchPosts();
    }, []);

    // Tạo bài viết
    const handleSubmit = async (e) => {
        e.preventDefault();
        
        try {
            const response = await fetch('http://localhost:8000/api/posts', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                },
                body: JSON.stringify({
                    title,
                    content,
                    user_id: 1
                })
            });
            
            if (!response.ok) throw new Error('Failed to create');
            
            const newPost = await response.json();
            setPosts([...posts, newPost.data]);
            setTitle('');
            setContent('');
        } catch (error) {
            setError(error.message);
        }
    };

    // Xóa bài viết
    const handleDelete = async (postId) => {
        try {
            const response = await fetch(
                `http://localhost:8000/api/posts/${postId}`,
                {
                    method: 'DELETE',
                    headers: {
                        'Authorization': 'Bearer ' + localStorage.getItem('token')
                    }
                }
            );
            
            if (!response.ok) throw new Error('Failed to delete');
            setPosts(posts.filter(p => p.id !== postId));
        } catch (error) {
            setError(error.message);
        }
    };

    if (loading) return <div>Loading...</div>;
    if (error) return <div>Error: {error}</div>;

    return (
        <div>
            <h1>Posts</h1>
            
            <form onSubmit={handleSubmit}>
                <input 
                    type="text" 
                    placeholder="Title"
                    value={title}
                    onChange={(e) => setTitle(e.target.value)}
                    required
                />
                <textarea 
                    placeholder="Content"
                    value={content}
                    onChange={(e) => setContent(e.target.value)}
                    required
                />
                <button type="submit">Create Post</button>
            </form>

            <div>
                {posts.map(post => (
                    <article key={post.id}>
                        <h2>{post.title}</h2>
                        <p>{post.content}</p>
                        <small>by {post.author}</small>
                        <button onClick={() => handleDelete(post.id)}>
                            Delete
                        </button>
                    </article>
                ))}
            </div>
        </div>
    );
}

export default PostsComponent;
```

#### Checklist Tích hợp Frontend - Backend

- [ ] Cấu hình CORS đúng trên backend
- [ ] Frontend có thể gọi GET API endpoints
- [ ] Frontend có thể gọi POST API endpoints
- [ ] Frontend xử lý validation errors từ API
- [ ] Frontend lưu token từ login
- [ ] Frontend gửi token trong Authorization header
- [ ] Frontend hiển thị dữ liệu từ API response
- [ ] Frontend cập nhật dữ liệu sau khi CREATE/UPDATE/DELETE
- [ ] Xử lý loading states trong frontend
- [ ] Xử lý error messages trong frontend
- [ ] Frontend tự động logout khi token hết hạn

---

## TÓM TẮT TOÀN BỘ NỘI DUNG

### API Development Workflow

```
1. DESIGN API
   ├─ Định nghĩa endpoints
   ├─ Xác định HTTP methods
   └─ Thiết kế response format

2. IMPLEMENT BACKEND
   ├─ Tạo routes (routes/api.php)
   ├─ Tạo API Controllers
   ├─ Tạo FormRequests để validation
   └─ Tạo API Resources để format response

3. IMPLEMENT AUTHENTICATION
   ├─ Tạo Auth Controller (register, login, logout)
   ├─ Cấu hình Sanctum tokens
   └─ Bảo vệ routes với middleware

4. TEST API
   ├─ Kiểm thử endpoints bằng Postman
   ├─ Kiểm thử validation
   └─ Kiểm thử authentication

5. CONFIGURE CORS
   ├─ Cấu hình allowed origins
   ├─ Cấu hình allowed methods
   └─ Cấu hình allowed headers

6. INTEGRATE FRONTEND
   ├─ Implement Fetch API / Axios
   ├─ Xử lý requests và responses
   ├─ Lưu tokens
   └─ Hiển thị dữ liệu
```

### HTTP Status Codes phổ biến

| Status | Meaning | Sử dụng |
|--------|---------|--------|
| 200 | OK | GET, PUT, PATCH thành công |
| 201 | Created | POST tạo resource mới thành công |
| 204 | No Content | DELETE thành công |
| 400 | Bad Request | Request không hợp lệ |
| 401 | Unauthorized | Không xác thực hoặc token không hợp lệ |
| 403 | Forbidden | Không có quyền truy cập |
| 404 | Not Found | Resource không tồn tại |
| 422 | Unprocessable Entity | Validation failed |
| 500 | Internal Server Error | Lỗi server |

### Best Practices

1. **Naming Conventions**
   - Routes: kebab-case (`/api/posts`, `/api/user-profiles`)
   - Methods: camelCase (`getUserPosts`)
   - Database: snake_case (`user_profiles`)

2. **Security**
   - Luôn validate input từ client
   - Sử dụng HTTPS trong production
   - Không expose sensitive data (passwords, tokens) trong response
   - Sử dụng rate limiting để ngăn chặn brute force attacks

3. **Response Format**
   - Luôn trả về consistent response structure
   - Bao gồm success flag, data, và message
   - Trả về proper HTTP status codes

4. **Error Handling**
   - Trả về meaningful error messages
   - Bao gồm validation errors chi tiết
   - Log errors trên server

5. **Pagination**
   - Implement pagination cho danh sách lớn
   - Trả về meta information (total, per_page, current_page)

6. **Versioning**
   - Cân nhắc API versioning nếu API sẽ thay đổi
   - Ví dụ: `/api/v1/posts`, `/api/v2/posts`

---

## HỎI & THẢO LUẬN

**Các câu hỏi đề xuất:**

1. Khác nhau giữa Stateful và Stateless authentication là gì?
2. Tại sao nên sử dụng API Resources thay vì trả về model trực tiếp?
3. Khi nào nên sử dụng PUT vs PATCH?
4. Làm thế nào để xử lý CORS errors?
5. Token-based authentication có những ưu điểm nào so với session-based?
6. Làm sao để test API endpoints hiệu quả?

---

## THE END!

**HEY! CODING IS EASY!**

---

*Ghi chú: Hướng dẫn này được thiết kế cho sinh viên bắt đầu học Laravel API development. Hãy thực hành từng phần và không ngại thử nghiệm!*