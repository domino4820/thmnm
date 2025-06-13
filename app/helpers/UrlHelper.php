<?php
class UrlHelper {
    /**
     * Lấy base URL của ứng dụng
     */
    public static function baseUrl() {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $domainName = $_SERVER['HTTP_HOST'];
        $baseUrl = $protocol . $domainName;

        // Kiểm tra thư mục cơ sở từ REQUEST_URI
        $basePath = '';
        
        // Thử xác định nếu có thư mục con (subdir) như /webbanhang/
        if (isset($_SERVER['REQUEST_URI'])) {
            $requestUri = $_SERVER['REQUEST_URI'];
            $scriptName = $_SERVER['SCRIPT_NAME'];
            
            // Lấy phần chung của path
            $dirName = dirname($scriptName);
            if ($dirName != '/' && $dirName != '\\') {
                if (strpos($requestUri, $dirName) === 0) {
                    $basePath = $dirName;
                }
            }
            
            // Debug info
            error_log("Request URI: " . $requestUri);
            error_log("Script Name: " . $scriptName);
            error_log("Base Path detected: " . $basePath);
        }
        
        return rtrim($baseUrl . $basePath, '/');
    }
    
    /**
     * Tạo URL đầy đủ từ đường dẫn tương đối
     */
    public static function url($path = '') {
        $baseUrl = self::baseUrl();
        $path = ltrim($path, '/');
        
        if (empty($path)) {
            return $baseUrl;
        }
        
        return $baseUrl . '/' . $path;
    }
    
    /**
     * Tạo đường dẫn đến file asset (css, js, hình ảnh)
     */
    public static function asset($path = '') {
        $path = ltrim($path, '/');
        return self::url('public/' . $path);
    }
    
    /**
     * Tạo đường dẫn đến hình ảnh
     */
    public static function image($path = '') {
        return self::asset('uploads/' . ltrim($path, '/'));
    }
}
?> 