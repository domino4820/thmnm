-- Create account table if not exists
CREATE TABLE IF NOT EXISTS `account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','employee','user') NOT NULL DEFAULT 'user',
  `email` varchar(100) DEFAULT NULL,
  `sdt` varchar(15) DEFAULT NULL,
  `dia_chi` text DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Add new columns if they don't exist
ALTER TABLE `account` 
  ADD COLUMN IF NOT EXISTS `email` varchar(100) DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS `sdt` varchar(15) DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS `dia_chi` text DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS `avatar` varchar(255) DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS `created_at` timestamp NOT NULL DEFAULT current_timestamp();

-- Insert admin user if not exists
INSERT IGNORE INTO `account` (`username`, `fullname`, `password`, `role`, `email`) 
VALUES ('admin', 'Administrator', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'admin@example.com');
-- Default password is 'password' 