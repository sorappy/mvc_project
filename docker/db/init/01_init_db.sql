USE db_test;
CREATE TABLE IF NOT EXISTS message_board(
  user_name VARCHAR(100),
  message VARCHAR(500),
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
