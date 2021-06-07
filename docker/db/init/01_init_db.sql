USE db_test;
CREATE TABLE IF NOT EXISTS message_board(
  user_name VARCHAR(100),
  message VARCHAR(500),
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO message_board (user_name, message) VALUES
('シロー', 'やあ、Tohu ENVヘ');
